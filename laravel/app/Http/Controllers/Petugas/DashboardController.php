<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPengangkutan;
use App\Models\SetoranSampah;
use Illuminate\Support\Facades\Auth;
use App\Models\KategoriSampah;
use Carbon\Carbon;

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

        // HANYA setoran pending yang SUDAH dijadwalkan untuk petugas ini HARI INI
        // Diurutkan berdasarkan waktu jemput terdekat
        $setoransHariIni = SetoranSampah::with(['user', 'kategori', 'jadwal'])
            ->where('status', 'pending')
            ->whereHas('jadwal', function ($query) {
                $query->where('petugas_id', Auth::id())
                      ->whereDate('tanggal', Carbon::today());
            })
            ->get()
            ->sortBy(fn($s) => optional($s->jadwal)->waktu_mulai)
            ->values();

        // HANYA setoran pending yang SUDAH dijadwalkan untuk petugas ini HARI MENDATANG
        // Diurutkan berdasarkan tanggal & waktu jemput terdekat
        $setoransMendatang = SetoranSampah::with(['user', 'kategori', 'jadwal'])
            ->where('status', 'pending')
            ->whereHas('jadwal', function ($query) {
                $query->where('petugas_id', Auth::id())
                      ->whereDate('tanggal', '>', Carbon::today());
            })
            ->get()
            ->sortBy(fn($s) => optional($s->jadwal)->tanggal . ' ' . optional($s->jadwal)->waktu_mulai)
            ->values();
       
        // Riwayat pengangkutan milik petugas ini
        $riwayatAngkut = SetoranSampah::with(['user', 'kategori'])
            ->where('petugas_id', Auth::id())
            ->whereIn('status', ['diangkut', 'selesai'])
            ->latest()
            ->get();

        return view('Petugas.dashboard', compact('jadwals', 'setoransHariIni', 'setoransMendatang', 'riwayatAngkut'));
    }
}