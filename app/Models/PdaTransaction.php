<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdaTransaction extends Model
{
    protected $table = 'pda_transactions';

    protected $fillable = [
        'pda_id',
        'employee_id',
        'borrowed_at',
        'returned_at',
        'duration_minutes',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function pda()
    {
        return $this->belongsTo(Pda::class);
    }
}
