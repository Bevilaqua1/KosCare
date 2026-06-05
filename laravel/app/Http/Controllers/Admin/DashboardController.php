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

    // Data untuk validasi
    $setoransValidasi = SetoranSampah::with(['user', 'kategori', 'jadwal'])
    ->whereIn('status', ['pending', 'diangkut'])
    ->latest()
    ->get();

    // untuk view user
    $users = User::latest()->get();
    return view('Admin.dashboard', compact(
        'kategoris', 'activeTab', 'totalPengguna', 'pendingSetoran', 'totalSampahTerkumpul',
        'setoransValidasi', 'jadwals', 'petugasList', 'users', 'rewards', 'penukarans'
    ));
    }
}