<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KategoriSampah;
use App\Models\SetoranSampah;
use Carbon\Carbon;

class SetoranSampahSeeder extends Seeder
{
    public function run()
    {
        // Ambil user dengan role resident (pastikan sudah ada dari ResidentSeeder)
        $residents = User::where('role', 'resident')->get();
        
        // Jika tidak ada resident, hentikan seeder
        if ($residents->isEmpty()) {
            $this->command->warn('Tidak ada user resident. Jalankan ResidentSeeder terlebih dahulu.');
            return;
        }

        // Ambil semua kategori (pastikan KategoriSampahSeeder sudah dijalankan)
        $kategoris = KategoriSampah::all();
        if ($kategoris->isEmpty()) {
            $this->command->warn('Tidak ada kategori sampah. Jalankan KategoriSampahSeeder terlebih dahulu.');
            return;
        }

        // Ambil user petugas (jika ada)
        $petugas = User::where('role', 'petugas')->first();

        // Data dummy setoran
        $dataSetoran = [
            [
                'user_id' => $residents->random()->id,
                'kategori_id' => $kategoris->where('nama_kategori', 'Plastik Botol')->first()->id ?? $kategoris->random()->id,
                'estimasi_berat' => 2.0,
                'tanggal_setor' => Carbon::now()->subDays(3),
                'foto' => null,
                'status' => 'pending',
                'berat_aktual' => null,
                'poin_didapat' => 0,
                'petugas_id' => null,
            ],
            [
                'user_id' => $residents->random()->id,
                'kategori_id' => $kategoris->where('nama_kategori', 'Kertas & Karton')->first()->id ?? $kategoris->random()->id,
                'estimasi_berat' => 5.5,
                'tanggal_setor' => Carbon::now()->subDays(2),
                'foto' => null,
                'status' => 'diangkut',
                'berat_aktual' => null,
                'poin_didapat' => 0,
                'petugas_id' => $petugas ? $petugas->id : null,
            ],
            [
                'user_id' => $residents->random()->id,
                'kategori_id' => $kategoris->where('nama_kategori', 'Plastik Botol')->first()->id ?? $kategoris->random()->id,
                'estimasi_berat' => 1.5,
                'tanggal_setor' => Carbon::now()->subDays(5),
                'foto' => null,
                'status' => 'selesai',
                'berat_aktual' => 1.8,
                'poin_didapat' => 18, // 1.8 * 10 poin/kg
                'petugas_id' => $petugas ? $petugas->id : null,
            ],
            [
                'user_id' => $residents->random()->id,
                'kategori_id' => $kategoris->where('nama_kategori', 'Organik')->first()->id ?? $kategoris->random()->id,
                'estimasi_berat' => 3.0,
                'tanggal_setor' => Carbon::now()->subDays(1),
                'foto' => null,
                'status' => 'pending',
                'berat_aktual' => null,
                'poin_didapat' => 0,
                'petugas_id' => null,
            ],
            [
                'user_id' => $residents->random()->id,
                'kategori_id' => $kategoris->random()->id,
                'estimasi_berat' => 4.2,
                'tanggal_setor' => Carbon::now()->subDays(4),
                'foto' => null,
                'status' => 'diangkut',
                'berat_aktual' => null,
                'poin_didapat' => 0,
                'petugas_id' => $petugas ? $petugas->id : null,
            ],
            [
                'user_id' => $residents->random()->id,
                'kategori_id' => $kategoris->random()->id,
                'estimasi_berat' => 6.0,
                'tanggal_setor' => Carbon::now()->subDays(6),
                'foto' => null,
                'status' => 'selesai',
                'berat_aktual' => 5.8,
                'poin_didapat' => 58, // asumsi poin_per_kg=10
                'petugas_id' => $petugas ? $petugas->id : null,
            ],
        ];

        // Insert semua data
        foreach ($dataSetoran as $data) {
            SetoranSampah::create($data);
        }

        $this->command->info('Seeder SetoranSampah berhasil dijalankan.');
    }
}