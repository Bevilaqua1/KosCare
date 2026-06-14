<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
           'name' => 'Petugas Lapangan',
            'email' => 'petugas@koscare.com',
            'password' => Hash::make('12345678'),
            'role' => 'petugas',
            'no_wa' => '08123456780',
            'no_kamar' => null,
            'nama_kos' => null,
            'alamat_kos' => null,
        ]);
    }
}
