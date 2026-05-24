<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Bank Sampah',
            'email' => 'admin@koscare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_kamar' => null,
            'no_wa' => '08123456789',
        ]);
    }
}