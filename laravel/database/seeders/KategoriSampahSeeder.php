<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriSampah;

class KategoriSampahSeeder extends Seeder
{
    public function run()
    {
        $kategoris = [
            [
                'nama_kategori' => 'Plastik Botol',
                'deskripsi' => 'Botol air mineral bersih tanpa isi',
                'poin_per_kg' => 10,
            ],
            [
                'nama_kategori' => 'Kertas & Karton',
                'deskripsi' => 'Kardus bekas, HVS, koran kering',
                'poin_per_kg' => 8,
            ],
            [
                'nama_kategori' => 'Logam & Kaca',
                'deskripsi' => 'Kaleng, besi, botol kaca',
                'poin_per_kg' => 15,
            ],  
        ];

        foreach ($kategoris as $kat) {
            KategoriSampah::create($kat);
        }
    }
}