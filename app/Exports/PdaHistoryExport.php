<?php

namespace App\Exports;

use App\Models\PdaTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PdaHistoryExport implements FromCollection, WithHeadings
{
    protected $from, $to, $nik, $pda_no;

    public function __construct($from, $to, $nik, $pda_no)
    {
        $this->from   = $from;
        $this->to     = $to;
        $this->nik    = $nik;
        $this->pda_no = $pda_no;
    }

    public function collection()
    {
        $query = PdaTransaction::with(['employee', 'pda']);

        if ($this->nik) {
            $query->whereHas('employee', fn ($q) =>
                $q->where('nik', $this->nik)
            );
        }

        if ($this->pda_no) {
            $query->whereHas('pda', fn ($q) =>
                $q->where('pda_no', $this->pda_no)
            );
        }

        if ($this->from) {
            $query->whereDate('borrowed_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->whereDate('borrowed_at', '<=', $this->to);
        }

        return $query->get()->map(function ($t) {
            return [
                $t->pda->pda_no,
                $t->employee->nik,
                $t->employee->name,
                $t->borrowed_at,
                $t->returned_at,
                $t->duration_minutes ? round($t->duration_minutes / 60, 2) : '-',
                strtoupper($t->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'PDA',
            'NIK',
            'Nama',
            'Pinjam',
            'Kembali',
            'Durasi (Jam)',
            'Status',
        ];
    }
}
