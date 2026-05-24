<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,      // yang sudah ada (admin)
            PetugasSeeder::class,
            ResidentSeeder::class,     // yang sudah ada (resident)
            KategoriSampahSeeder::class, // jika ada
            KategoriRewardSeeder::class, // SetoranSampahSeeder::class (nanti sesuaikan dengan field baru)
        ]);
    }
}