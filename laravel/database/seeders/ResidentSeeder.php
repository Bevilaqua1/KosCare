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
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@koscare.com',
                'password' => Hash::make('12345678'),
                'role' => 'resident',
                'no_wa' => '0812000002',
                'no_kamar' => 'B-202',
            ],
            [
                'name' => 'Budi Hartono',
                'email' => 'budi@koscare.com',
                'password' => Hash::make('12345678'),
                'role' => 'resident',
                'no_wa' => '0812000003',
                'no_kamar' => 'A-102',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@koscare.com',
                'password' => Hash::make('12345678'),
                'role' => 'resident',
                'no_wa' => '0812000004',
                'no_kamar' => 'C-301',
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi@koscare.com',
                'password' => Hash::make('12345678'),
                'role' => 'resident',
                'no_wa' => '0812000005',
                'no_kamar' => 'B-201',
            ],
        ];

        foreach ($residents as $resident) {
            User::create($resident);
        }

        // Opsional: gunakan factory untuk membuat banyak data acak
        // User::factory()->count(10)->create(['role' => 'resident']);
    }
}