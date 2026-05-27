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
            ResidentSeeder::class,     // yang sudah ada (resident)php a
            KategoriSampahSeeder::class, // jika ada
            KategoriRewardSeeder::class, 
            SetoranSampahSeeder::class, // jika sudah ada kategori dan user
        ]);
    }
}