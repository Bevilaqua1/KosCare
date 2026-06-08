<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        // Data penghuni kos dummy
        $residents = [
            [
                'name' => 'Andi Prasetyo',
                'email' => 'andi@koscare.com',
                'password' => Hash::make('12345678'),
                'role' => 'resident',
                'no_wa' => '0812000001',
                'no_kamar' => 'A-101',
                'nama_kos' => 'Kos Melati',
                'alamat_kos' => 'Jl. Mendalo Indah No.12',
            ],
        ];

        foreach ($residents as $resident) {
            User::create($resident);
        }

        // Opsional: gunakan factory untuk membuat banyak data acak
        // User::factory()->count(10)->create(['role' => 'resident']);
    }
}