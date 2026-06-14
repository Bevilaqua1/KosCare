<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriSampah;
use App\Models\SetoranSampah;
use App\Models\JadwalPengangkutan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\KategoriReward;
use App\Models\PenukaranPoin;
use App\Models\ArtikelEdukasi;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = KategoriSampah::all();
        $activeTab = $request->query('tab', 'dash-penghuni');

        // Riwayat setoran milik penghuni yang sedang login
        $riwayatSetoran = SetoranSampah::with('kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $totalSetoran = $riwayatSetoran->count();
        // Total poin dari setoran yang sudah selesai
        $totalPoinDiterima = SetoranSampah::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->sum('poin_didapat');

        // Total poin yang sudah digunakan (penukaran disetujui & pending)
        $totalPoinDipakai = PenukaranPoin::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum('total_poin');

        // Sisa saldo poin
        $totalPoin = max(0, $totalPoinDiterima - $totalPoinDipakai);

        // Semua jadwal (untuk tab Jadwal Angkut) - hanya jadwal yang terkait dengan setoran user saat ini dan status pending
        $jadwals = JadwalPengangkutan::whereHas('setorans', function ($q) {
            $q->where('user_id', Auth::id())
              ->where('status', 'pending');
            })
            ->with('petugas')
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        // Jadwal terdekat (untuk kartu Ikhtisar) - hanya jadwal dari setoran user dengan status pending dan tanggal hari ini atau setelahnya
        $jadwalTerdekat = JadwalPengangkutan::whereHas('setorans', function ($q) {
            $q->where('user_id', Auth::id())
              ->where('status', 'pending');
            })
            ->with('petugas')
            ->where('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->first();

        // penukaran poin
        $rewards = KategoriReward::where('stok', '>', 0)->get();
        $riwayatPenukaran = PenukaranPoin::with('kategoriReward')
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->get();

        // Artikel edukasi
        $artikels = ArtikelEdukasi::latest('tanggal_terbit')->get();


        return view('Resident.dashboard', compact(
            'activeTab',
            'kategoris',
            'riwayatSetoran',
            'totalSetoran',
            'totalPoin',
            'jadwals',
            'jadwalTerdekat',
            'rewards',
            'riwayatPenukaran',
            'artikels',
        ));
    }
}