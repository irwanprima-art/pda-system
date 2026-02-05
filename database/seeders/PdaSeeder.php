<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pda;

class PdaSeeder extends Seeder
{
    public function run(): void
    {
        $pdas = [
            'PDA_INVINB_01',
            'PDA_INVINB_02',
            'PDA_INVINB_03',
            'PDA_INVINB_04',
            'PDA_INVINB_05',
            'PDA_INVINB_06',
            'PDA_INVINB_07',
            'PDA_INVINB_08',
            'PDA_INVINB_09',
            'PDA_INVINB_10',
            'PDA_INVINB_11',
            'PDA_INVINB_12',
        ];

        foreach ($pdas as $pdaNo) {
            Pda::updateOrCreate(
                ['pda_no' => $pdaNo],
                ['status' => 'available']
            );
        }
    }
}
