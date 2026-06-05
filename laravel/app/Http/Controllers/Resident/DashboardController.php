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
        $totalPoin = $riwayatSetoran->sum('poin_didapat');

        // Semua jadwal (untuk tab Jadwal Angkut)
        $jadwals = JadwalPengangkutan::with('petugas')
            ->whereHas('setorans', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        // Jadwal terdekat (untuk kartu Ikhtisar)
        $jadwalTerdekat = JadwalPengangkutan::with('petugas')
            ->whereHas('setorans', function ($query) {
                $query->where('user_id', Auth::id());
            })
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

        return view('Resident.dashboard', compact(
            'kategoris',
            'activeTab',
            'riwayatSetoran',
            'totalSetoran',
            'totalPoin',
            'jadwals',
            'jadwalTerdekat',
            'rewards',
            'riwayatPenukaran'      
        ));
    }
}