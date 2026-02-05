<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pda;
use App\Models\Employee;
use App\Models\PdaTransaction;
use App\Helpers\Audit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PdaTransactionController extends Controller
{
    // =========================
    // PINJAM PDA
    // =========================
    public function borrow(Request $request)
    {
        $request->validate([
            'nik'    => 'required|exists:employees,nik',
            'pda_no' => 'required|exists:pdas,pda_no',
        ]);

        $employee = Employee::where('nik', $request->nik)->first();
        $pda      = Pda::where('pda_no', $request->pda_no)->first();
    
        $activeBorrow = PdaTransaction::where('employee_id', $employee->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($activeBorrow) {
            return response()->json([
                'message' => 'Karyawan masih memiliki PDA yang belum dikembalikan'
            ], 422);
        }

        if ($pda->status !== 'available') {
            return response()->json([
                'message' => 'PDA sedang dipinjam'
            ], 422);
        }

        if ($employee->status === 'nonaktif') {
            return response()->json([
                'message' => 'Karyawan tidak aktif'
            ], 422);
        }

        DB::transaction(function () use ($employee, $pda, $request) {
            PdaTransaction::create([
                'pda_id'      => $pda->id,
                'employee_id' => $employee->id,
                'borrowed_at' => Carbon::now(),
                'status'      => 'borrowed',
            ]);

            $pda->update([
                'status' => 'borrowed'
            ]);

            Audit::log(
                'BORROW',
                "PDA {$pda->pda_no} dipinjam oleh {$employee->nik}",
                $request
            );
        });

        return response()->json([
            'message' => 'PDA berhasil dipinjam'
        ]);
    }

    // =========================
    // KEMBALIKAN PDA
    // =========================
    public function return(Request $request)
    {
        $request->validate([
            'pda_no' => 'required|exists:pdas,pda_no',
        ]);

        $pda = Pda::where('pda_no', $request->pda_no)->first();

        $transaction = PdaTransaction::where('pda_id', $pda->id)
            ->where('status', 'borrowed')
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Tidak ada transaksi aktif untuk PDA ini'
            ], 422);
        }

        $returnedAt = Carbon::now();
        $borrowedAt = Carbon::parse($transaction->borrowed_at);

        $durationMinutes = $borrowedAt->diffInMinutes($returnedAt);
        $finalStatus = $durationMinutes > (9 * 60) ? 'overdue' : 'returned';

        DB::transaction(function () use (
            $transaction,
            $returnedAt,
            $durationMinutes,
            $finalStatus,
            $pda,
            $request
        ) {
            $transaction->update([
                'returned_at'       => $returnedAt,
                'duration_minutes' => $durationMinutes,
                'status'            => $finalStatus,
            ]);

            $pda->update([
                'status' => 'available'
            ]);

            Audit::log(
                'RETURN',
                "PDA {$pda->pda_no} dikembalikan (status: {$finalStatus})",
                $request
            );
        });

        return response()->json([
            'message' => 'PDA berhasil dikembalikan',
            'duration_minutes' => $durationMinutes,
            'status' => $finalStatus
        ]);
    }

    // =========================
    // STATUS PDA (MONITORING)
    // =========================
    public function status()
    {
        $pdas = Pda::with([
            'transactions' => function ($q) {
                $q->where('status', 'borrowed')->latest();
            },
            'transactions.employee'
        ])->get();

        $result = [];

        foreach ($pdas as $pda) {
            $transaction = $pda->transactions->first();

            if ($transaction) {
                $borrowedAt = Carbon::parse($transaction->borrowed_at);
                $now = Carbon::now();

                $durationMinutes = $borrowedAt->diffInMinutes($now);
                $overdue = $durationMinutes > (9 * 60);

                $result[] = [
                    'pda_no' => $pda->pda_no,
                    'status' => $overdue ? 'overdue' : 'borrowed',
                    'nik' => $transaction->employee->nik,
                    'nama' => $transaction->employee->nama,
                    'duration_minutes' => $durationMinutes,
                    'duration_hours' => round($durationMinutes / 60, 2),
                    'overdue' => $overdue,
                ];
            } else {
                $result[] = [
                    'pda_no' => $pda->pda_no,
                    'status' => 'available',
                    'nik' => null,
                    'nama' => null,
                    'duration_minutes' => null,
                    'duration_hours' => null,
                    'overdue' => false,
                ];
            }
        }

        return response()->json($result);
    }

    // =========================
    // HISTORY PEMINJAMAN PDA
    // =========================
    public function history(Request $request)
    {
        $query = PdaTransaction::with(['employee', 'pda']);

        if ($request->filled('pda_no')) {
            $query->whereHas('pda', function ($q) use ($request) {
                $q->where('pda_no', $request->pda_no);
            });
        }

        if ($request->filled('nik')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('nik', $request->nik);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('borrowed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('borrowed_at', '<=', $request->date_to);
        }

        $transactions = $query
            ->orderBy('borrowed_at', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'pda_no' => $t->pda->pda_no,
                    'nik' => $t->employee->nik,
                    'nama' => $t->employee->nama,
                    'borrowed_at' => $t->borrowed_at,
                    'returned_at' => $t->returned_at,
                    'duration_minutes' => $t->duration_minutes,
                    'status' => $t->status,
                ];
            });

        return response()->json([
            'total' => $transactions->count(),
            'data' => $transactions,
        ]);
    }

    // =========================
    // DASHBOARD SUMMARY
    // =========================
    public function dashboard()
    {
        $totalPda = Pda::count();
        $available = Pda::where('status', 'available')->count();

        $borrowedTransactions = PdaTransaction::where('status', 'borrowed')->get();

        $borrowed = $borrowedTransactions->count();
        $overdue = 0;

        foreach ($borrowedTransactions as $t) {
            $borrowedAt = Carbon::parse($t->borrowed_at);
            $durationMinutes = $borrowedAt->diffInMinutes(Carbon::now());

            if ($durationMinutes > (9 * 60)) {
                $overdue++;
            }
        }

        return response()->json([
            'total_pda' => $totalPda,
            'available' => $available,
            'borrowed' => $borrowed,
            'overdue' => $overdue,
        ]);
    }

    // =====================================================
    // ✅ METHOD BARU (TAMBAHAN SAJA – TIDAK MENGUBAH SISTEM)
    // =====================================================
    protected function validateMasterData(?string $nik, ?string $pdaNo)
    {
        if ($nik && !Employee::where('nik', $nik)->exists()) {
            return 'NIK tidak terdaftar';
        }

        if ($pdaNo && !Pda::where('pda_no', $pdaNo)->exists()) {
            return 'PDA tidak terdaftar';
        }

        return null;
    }
}
