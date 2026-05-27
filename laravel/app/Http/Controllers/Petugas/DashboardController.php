<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPengangkutan;
use App\Models\SetoranSampah;
use Illuminate\Support\Facades\Auth;

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

        // Setoran pending (tugas hari ini)
        $setorans = SetoranSampah::with(['user', 'kategori'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('Petugas.dashboard', compact('jadwals', 'setorans'));
    }

}