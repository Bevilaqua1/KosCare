<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriSampah;

class KategoriSampahSeeder extends Seeder
{
    public function run()
    {
        $kategoris = [
            ['nama_kategori' => 'Organik', 'deskripsi' => 'Sisa makanan, daun, dll.','poin_per_kg' => 10],
            ['nama_kategori' => 'Anorganik', 'deskripsi' => 'Plastik, kaca, logam, dll.','poin_per_kg' => 5],
            ['nama_kategori' => 'B3', 'deskripsi' => 'Bahan Berbahaya dan Beracun (baterai, lampu, dll.)','poin_per_kg' => 20],
        ];

        foreach ($kategoris as $kat) {
            KategoriSampah::create($kat);
        }
    }
}