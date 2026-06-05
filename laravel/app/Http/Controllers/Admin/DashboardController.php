<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriSampah;
use App\Models\User;
use App\Models\SetoranSampah;
use App\Models\JadwalPengangkutan;
use App\Models\KategoriReward;
use App\Models\PenukaranPoin;
use App\Models\ArtikelEdukasi;
use carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = KategoriSampah::all();
        $activeTab = $request->query('tab', 'dash-admin'); // default tab ikhtisar
        $totalPengguna = User::count();
        $pendingSetoran = SetoranSampah::where('status', 'pending')->count();
        $totalSampahTerkumpul = SetoranSampah::where('status', 'selesai')->sum('berat_aktual');
        $jadwals = JadwalPengangkutan::with('petugas')->latest()->get();
        $petugasList = User::where('role', 'petugas')->get();
        $rewards = KategoriReward::latest()->get();
        $penukarans = PenukaranPoin::with(['user', 'kategoriReward'])->where('status', 'pending')->latest()->get();
        $setoransValidasi = SetoranSampah::with(['user', 'kategori', 'jadwal'])->whereIn('status', ['pending', 'diangkut'])->latest()->get();
        $artikels = ArtikelEdukasi::latest()->get();
        $pendingValidationCount = SetoranSampah::where('status', 'diangkut')->count();
// untuk grafik laporan
        // Data untuk grafik: total sampah per bulan (dari setoran selesai)
        $completedSetoran = SetoranSampah::where('status', 'selesai')->get();
        $monthlyData = $completedSetoran->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_setor)->format('Y-m'); // contoh: "2026-05"
        })->map(function ($group) {
            return $group->sum('berat_aktual');
        });

        $chartLabels = $monthlyData->keys()->map(function ($label) {
            return Carbon::createFromFormat('Y-m', $label)->translatedFormat('M Y'); // label: "Mei 2026"
        })->toArray();
        $chartValues = $monthlyData->values()->toArray();

        // Data untuk pie chart: perbandingan kategori sampah
        $kategoriData = $completedSetoran->groupBy('kategori_id')->map(function ($group) {
            $kategori = $group->first()->kategori;
            return [
                'nama' => $kategori->nama_kategori ?? '-',
                'total' => $group->sum('berat_aktual')
            ];
        })->values();

        $pieLabels = $kategoriData->pluck('nama')->toArray();
        $pieValues = $kategoriData->pluck('total')->toArray();

// untuk view user
    $users = User::latest()->get();

    return view('Admin.dashboard', compact(
        'kategoris', 'activeTab', 'totalPengguna', 'pendingSetoran', 'totalSampahTerkumpul',
        'setoransValidasi', 'jadwals', 'petugasList', 'users', 'rewards', 'penukarans','artikels', 
        'chartLabels', 'chartValues', 'pieLabels', 'pieValues', 'pendingValidationCount'
    ));
    }
}