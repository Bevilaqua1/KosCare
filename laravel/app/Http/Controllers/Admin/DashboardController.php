<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriSampah;
use App\Models\User;
use App\Models\SetoranSampah;
use App\Models\JadwalPengangkutan;

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

    // Data untuk validasi
    $setoransValidasi = SetoranSampah::with(['user', 'kategori'])
                ->where('status', 'diangkut')
                ->latest()
                ->get();

    return view('Admin.dashboard', compact(
        'kategoris', 'activeTab', 'totalPengguna', 'pendingSetoran', 'totalSampahTerkumpul',
        'setoransValidasi', 'jadwals', 'petugasList'
    ));
    }
}