<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KategoriSampah;
use App\Models\SetoranSampah;
use App\Models\JadwalPengangkutan;
use Carbon\Carbon;

class SetoranSampahSeeder extends Seeder
{
    public function run()
    {
        // Ambil user Andi
        $andi = User::where('email', 'andi@koscare.com')->first();
        if (!$andi) {
            $this->command->warn('ResidentSeeder harus dijalankan terlebih dahulu.');
            return;
        }

        // Ambil kategori
        $plastik = KategoriSampah::where('nama_kategori', 'Plastik Botol')->first();
        $kertas  = KategoriSampah::where('nama_kategori', 'Kertas & Karton')->first();
        $organik = KategoriSampah::where('nama_kategori', 'Organik')->first();
        $logam   = KategoriSampah::where('nama_kategori', 'Logam & Kaca')->first();

        if (!$plastik || !$kertas || !$organik || !$logam) {
            $this->command->warn('KategoriSampahSeeder harus dijalankan terlebih dahulu.');
            return;
        }

        // Ambil petugas
        $petugas = User::where('role', 'petugas')->first();

        // 1. Pending (tanpa jadwal) → Admin bisa langsung jadwalkan saat demo
        SetoranSampah::create([
            'user_id'       => $andi->id,
            'kategori_id'   => $plastik->id,
            'estimasi_berat'=> 2.0,
            'tanggal_setor' => Carbon::now()->subDays(1),
            'foto'          => null,
            'status'        => 'pending',
            'berat_aktual'  => null,
            'poin_didapat'  => 0,
            'petugas_id'    => null,
            'jadwal_id'     => null,
        ]);

        // 2. Pending (sudah dijadwalkan) → Petugas bisa langsung konfirmasi angkut
        $jadwal1 = JadwalPengangkutan::create([
            'tanggal'      => Carbon::now()->addDays(1),
            'waktu_mulai'  => '08:00',
            'waktu_selesai'=> '10:00',
            'petugas_id'   => $petugas ? $petugas->id : null,
            'keterangan'   => 'Jemput setoran plastik Andi (dijadwalkan)',
        ]);

        SetoranSampah::create([
            'user_id'       => $andi->id,
            'kategori_id'   => $plastik->id,
            'estimasi_berat'=> 3.5,
            'tanggal_setor' => Carbon::now()->subDays(2),
            'foto'          => null,
            'status'        => 'pending',
            'berat_aktual'  => null,
            'poin_didapat'  => 0,
            'petugas_id'    => $petugas ? $petugas->id : null,
            'jadwal_id'     => $jadwal1->id,
        ]);

        // 3. Diangkut (siap divalidasi) → Admin bisa langsung validasi
        $jadwal2 = JadwalPengangkutan::create([
            'tanggal'      => Carbon::now(),
            'waktu_mulai'  => '09:00',
            'waktu_selesai'=> '11:00',
            'petugas_id'   => $petugas ? $petugas->id : null,
            'keterangan'   => 'Jemput setoran kertas Andi (sudah diangkut)',
        ]);

        SetoranSampah::create([
            'user_id'       => $andi->id,
            'kategori_id'   => $kertas->id,
            'estimasi_berat'=> 5.0,
            'tanggal_setor' => Carbon::now()->subDays(3),
            'foto'          => null,
            'status'        => 'diangkut',
            'berat_aktual'  => null,
            'poin_didapat'  => 0,
            'petugas_id'    => $petugas ? $petugas->id : null,
            'jadwal_id'     => $jadwal2->id,
        ]);

        // 4. Selesai (poin diberikan) → Menambah saldo poin Andi
        $jadwal3 = JadwalPengangkutan::create([
            'tanggal'      => Carbon::now()->subDays(1),
            'waktu_mulai'  => '07:00',
            'waktu_selesai'=> '08:00',
            'petugas_id'   => $petugas ? $petugas->id : null,
            'keterangan'   => 'Jemput setoran organik Andi (selesai)',
        ]);

        SetoranSampah::create([
            'user_id'       => $andi->id,
            'kategori_id'   => $organik->id,
            'estimasi_berat'=> 2.0,
            'tanggal_setor' => Carbon::now()->subDays(5),
            'foto'          => null,
            'status'        => 'selesai',
            'berat_aktual'  => 2.2,
            'poin_didapat'  => 22,   // 2.2 kg × 10 poin
            'petugas_id'    => $petugas ? $petugas->id : null,
            'jadwal_id'     => $jadwal3->id,
        ]);

        // 5. Ditolak → Riwayat penolakan untuk Andi
        SetoranSampah::create([
            'user_id'       => $andi->id,
            'kategori_id'   => $logam->id,
            'estimasi_berat'=> 1.0,
            'tanggal_setor' => Carbon::now()->subDays(4),
            'foto'          => null,
            'status'        => 'ditolak',
            'berat_aktual'  => null,
            'poin_didapat'  => 0,
            'petugas_id'    => null,
            'jadwal_id'     => null,
        ]);

        $this->command->info('SetoranSampahSeeder untuk Andi berhasil dijalankan.');
    }
}