<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use Illuminate\Http\Request;

class SetoranController extends Controller
{
    // Tampilkan semua setoran yang perlu divalidasi (status = 'diangkut')
    public function index()
    {
        $setorans = SetoranSampah::with(['user', 'kategori'])
                    ->where('status', 'diangkut')
                    ->latest()
                    ->get();
        return view('Admin.setoran.index', compact('setorans'));
    }

    // Method ini akan dipanggil dari dashboard admin (tab validasi)
    // Kita sebenarnya bisa langsung menampilkan data di dashboard tanpa redirect ke halaman terpisah,
    // tetapi untuk kemudahan testing awal kita akan buat aksinya dulu.
    
    public function verifikasi(Request $request, SetoranSampah $setoran)
    {
        $request->validate([
            'berat_aktual' => 'required|numeric|min:0.1',
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
}