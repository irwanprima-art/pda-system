<?php

namespace App\Http\Controllers;

use App\Models\Pda;
use App\Models\PdaTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function tv()
    {
        // Semua PDA + transaksi aktif
        $pdas = Pda::with(['activeTransaction.employee'])
            ->orderBy('pda_no')
            ->get();

        // Summary
        $total     = $pdas->count();
        $available = $pdas->where('status', 'available')->count();
        $borrowed  = $pdas->where('status', 'borrowed')->count();

        // Hitung overdue (>9 jam)
        $overdue = 0;
        foreach ($pdas as $pda) {
            if ($pda->activeTransaction) {
                $hours = Carbon::parse($pda->activeTransaction->borrowed_at)
                    ->diffInHours(now());

                if ($hours > 9) {
                    $overdue++;
                }
            }
        }

        return view('dashboard.tv', compact(
            'pdas',
            'total',
            'available',
            'borrowed',
            'overdue'
        ));
    }
}
