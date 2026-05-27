<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    // Menampilkan daftar setoran yang siap diangkut (status pending)
    public function index()
    {
        $setorans = SetoranSampah::with(['user', 'kategori'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('Petugas.dashboard', compact('setorans'));
    }

    // Konfirmasi pengangkutan
    public function konfirmasi(SetoranSampah $setoran)
    {
        // Hanya setoran pending yang bisa diangkut
        if ($setoran->status !== 'pending') {
            return back()->with('error', 'Setoran ini sudah diproses.');
        }

        $setoran->update([
            'status' => 'diangkut',
            'petugas_id' => Auth::id(),
        ]);

        return redirect()->route('petugas.dashboard')
            ->with('success', 'Setoran berhasil dikonfirmasi pengangkutan.');
    }
}