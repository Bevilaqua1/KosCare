<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPengangkutan;
use App\Models\SetoranSampah;
use Illuminate\Support\Facades\Auth;
USE App\Models\KategoriSampah;

class DashboardController extends Controller
{
    public function index()
    {
        // Jadwal yang ditugaskan kepada petugas ini
        $jadwals = JadwalPengangkutan::with('petugas')
            ->where('petugas_id', Auth::id())
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        // HANYA setoran pending yang SUDAH dijadwalkan untuk petugas ini
        $setorans = SetoranSampah::with(['user', 'kategori', 'jadwal'])
            ->where('status', 'pending')
            ->whereHas('jadwal', function ($query) {
                $query->where('petugas_id', Auth::id());
            })
            ->latest()
            ->get();

       
        // Riwayat pengangkutan milik petugas ini
        $riwayatAngkut = SetoranSampah::with(['user', 'kategori'])
            ->where('petugas_id', Auth::id())
            ->whereIn('status', ['diangkut', 'selesai'])
            ->latest()
            ->get();

        return view('Petugas.dashboard', compact('jadwals', 'setorans', 'riwayatAngkut'));
    }
}