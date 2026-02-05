<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pda extends Model
{
    protected $table = 'pdas';

    protected $fillable = [
        'pda_no',
        'status',
    ];

    /**
     * Semua history transaksi PDA
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PdaTransaction::class, 'pda_id');
    }

    /**
     * Transaksi aktif (sedang dipinjam)
     * Dipakai untuk:
     * - Dashboard TV
     * - Status real-time
     */
    public function activeTransaction(): HasOne
    {
        return $this->hasOne(PdaTransaction::class, 'pda_id')
            ->where('status', 'borrowed');
    }
}
