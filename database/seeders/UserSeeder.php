<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::updateOrCreate(
            ['email' => 'admin@pda.local'],
            [
                'name' => 'Admin PDA',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // OPERATOR
        User::updateOrCreate(
            ['email' => 'operator@pda.local'],
            [
                'name' => 'Operator PDA',
                'password' => Hash::make('operator123'),
                'role' => 'operator',
            ]
        );

        // MONITOR (TV)
        User::updateOrCreate(
            ['email' => 'monitor@pda.local'],
            [
                'name' => 'Monitor PDA',
                'password' => Hash::make('monitor123'),
                'role' => 'monitor',
            ]
        );
    }
}
