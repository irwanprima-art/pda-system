<?php

namespace App\Http\Controllers;

use App\Models\PdaTransaction;
use Illuminate\Support\Facades\DB;

class SummaryDashboardController extends Controller
{
    public function index()
    {
        // ===============================
        // SUMMARY PER KARYAWAN
        // ===============================
        $perEmployee = PdaTransaction::with(['employee', 'pda'])
            ->whereNotNull('returned_at')
            ->get()
            ->groupBy('employee_id')
            ->map(function ($rows) {
                return [
                    'employee' => $rows->first()->employee,
                    'total_pinjam' => $rows->count(),
                    'total_jam' => round($rows->sum('duration_minutes') / 60, 1),
                    'details' => $rows
                ];
            });

        // ===============================
        // SUMMARY PER PDA
        // ===============================
        $perPda = PdaTransaction::with(['employee', 'pda'])
            ->whereNotNull('returned_at')
            ->get()
            ->groupBy('pda_id')
            ->map(function ($rows) {
                return [
                    'pda' => $rows->first()->pda,
                    'total_pinjam' => $rows->count(),
                    'total_jam' => round($rows->sum('duration_minutes') / 60, 1),
                    'details' => $rows
                ];
            });

        return view('dashboard.summary', compact('perEmployee', 'perPda'));
    }
}
