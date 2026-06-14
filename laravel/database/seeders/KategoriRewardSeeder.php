<?php

namespace Database\Seeders;

use App\Models\KategoriReward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $items = [
            ['nama_item' => 'Detergen 1kg', 'poin_diperlukan' => 100, 'stok' => 20],
            ['nama_item' => 'Sabun Mandi', 'poin_diperlukan' => 50, 'stok' => 50],
            ['nama_item' => 'Token Listrik 20k', 'poin_diperlukan' => 200, 'stok' => 10],
        ];
        foreach ($items as $item) {
            KategoriReward::create($item);
        }
    }
}