<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JadwalPengangkutan;

class SetoranController extends Controller
{
    // Tampilkan semua setoran yang perlu divalidasi (status = 'diangkut')
    public function index()
    {
        $setoransValidasi = SetoranSampah::with(['user', 'kategori', 'jadwal'])
    ->whereIn('status', ['pending', 'diangkut'])
    ->latest()
    ->get();
    return redirect()->route('admin.dashboard', ['tab' => 'validasi-admin']);    
    }

    // Method ini akan dipanggil dari dashboard admin (tab validasi)
    // Kita sebenarnya bisa langsung menampilkan data di dashboard tanpa redirect ke halaman terpisah,
    // tetapi untuk kemudahan testing awal kita akan buat aksinya dulu.
    
    public function verifikasi(Request $request, SetoranSampah $setoran)
    {
        $request->validate([
            'berat_aktual' => 'required|numeric|min:0',
        ], [
            'berat_aktual.required' => 'Berat aktual wajib diisi.',
            'berat_aktual.numeric' => 'Berat aktual harus berupa angka.',
            'berat_aktual.min' => 'Berat aktual minimal adalah 0 Kg.',
        ]);

        $berat = $request->berat_aktual;
        $poin = 0;

        // Hitung poin berdasarkan kategori
        if ($setoran->kategori && $setoran->kategori->poin_per_kg > 0) {
            $poin = $berat * $setoran->kategori->poin_per_kg;
        }

        // Update setoran
        $setoran->update([
            'berat_aktual' => $berat,
            'poin_didapat' => $poin,
            'status' => 'selesai',
        ]);

        // Tambahkan poin ke user (jika ada field total_poin di user, jika tidak, kita bisa hitung dari relasi)
        // Asumsikan user memiliki field 'total_poin' di tabel users? Tidak ada di desain awal, jadi kita
        // akan menghitung poin total dari relasi setoran nanti. Untuk sekarang kita biarkan dulu.
        
        // Jika ingin menyimpan total poin di user, bisa ditambahkan kolom 'total_poin' di users.
        // Untuk saat ini, kita hanya mencatat di setoran.

        return redirect()->route('admin.dashboard', ['tab' => 'validasi-admin'])
            ->with('success', 'Setoran berhasil diverifikasi! Poin diberikan: ' . $poin);
    }

    // Method untuk menolak setoran
    public function tolak(Request $request, SetoranSampah $setoran)
    {
        $setoran->update(['status' => 'ditolak']);
        return redirect()->route('admin.dashboard', ['tab' => 'validasi-admin'])
            ->with('success', 'Setoran ditolak.');
    }

    // Menampilkan form jadwal (untuk modal, bisa via AJAX)
    public function jadwalkanForm(SetoranSampah $setoran)
    {
        $petugasList = User::where('role', 'petugas')->get();
        if (request()->ajax()) {
            return response()->json([
                'setoran' => $setoran,
                'petugasList' => $petugasList
            ]);
        }
        return view('Admin.setoran.jadwalkan', compact('setoran', 'petugasList'));
    }

    // Menyimpan jadwal baru untuk setoran
    public function jadwalkan(Request $request, SetoranSampah $setoran)
    {
        // Gabungkan tanggal_jemput dengan waktu_mulai dan waktu_selesai
        if ($request->filled(['tanggal_jemput', 'waktu_mulai'])) {
            $request->merge([
                'waktu_mulai' => $request->tanggal_jemput . ' ' . $request->waktu_mulai
            ]);
        }
        if ($request->filled(['tanggal_jemput', 'waktu_selesai'])) {
            $request->merge([
                'waktu_selesai' => $request->tanggal_jemput . ' ' . $request->waktu_selesai
            ]);
        }

        $request->validate([
            'tanggal_jemput' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date|after:now',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'petugas_id' => 'required|exists:users,id',
            'keterangan' => 'nullable|string',
        ], [
            'tanggal_jemput.required' => 'Tanggal jemput wajib diisi.',
            'tanggal_jemput.date' => 'Tanggal jemput tidak valid.',
            'tanggal_jemput.after_or_equal' => 'Tanggal jemput harus hari ini atau tanggal setelahnya.',
            'waktu_mulai.required' => 'Waktu mulai penjemputan wajib diisi.',
            'waktu_mulai.after' => 'Waktu mulai penjemputan harus setelah waktu sekarang.',
            'waktu_selesai.required' => 'Waktu selesai penjemputan wajib diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'petugas_id.required' => 'Petugas wajib dipilih.',
            'petugas_id.exists' => 'Petugas yang dipilih tidak terdaftar.',
        ]);

        // Buat jadwal baru
        $jadwal = JadwalPengangkutan::create([
            'tanggal' => $request->tanggal_jemput,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'petugas_id' => $request->petugas_id,
            'keterangan' => $request->keterangan,
        ]);

        // Hubungkan setoran ke jadwal
        $setoran->update([
            'jadwal_id' => $jadwal->id,
            'petugas_id' => $request->petugas_id,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'validasi-admin'])
            ->with('success', 'Jadwal penjemputan berhasil dibuat untuk setoran #' . $setoran->id);
    }

}