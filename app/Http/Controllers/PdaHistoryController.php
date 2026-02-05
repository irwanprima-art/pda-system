<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdaTransaction;
use App\Exports\PdaHistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class PdaHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = collect(); // default kosong

        // JALANKAN QUERY JIKA ADA SALAH SATU FILTER
        if ($request->hasAny(['nik', 'pda_no', 'from', 'to'])) {

            $query = PdaTransaction::with(['employee', 'pda'])
                ->orderByDesc('borrowed_at');

            if ($request->filled('nik')) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('nik', $request->nik);
                });
            }

            if ($request->filled('pda_no')) {
                $query->whereHas('pda', function ($q) use ($request) {
                    $q->where('pda_no', $request->pda_no);
                });
            }

            if ($request->filled('from')) {
                $query->whereDate('borrowed_at', '>=', $request->from);
            }

            if ($request->filled('to')) {
                $query->whereDate('borrowed_at', '<=', $request->to);
            }

            $histories = $query
                ->paginate(15)
                ->withQueryString();
        }

        return view('history.index', compact('histories'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new PdaHistoryExport(
                $request->from,
                $request->to,
                $request->nik,
                $request->pda_no
            ),
            'history_pda.xlsx'
        );
    }
}
