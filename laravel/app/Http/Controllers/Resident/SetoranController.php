<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\KategoriSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SetoranController extends Controller
{
    // Menampilkan form (sebenarnya sudah ada di dashboard, jadi bisa diabaikan)
    public function create()
    {
        $kategoris = KategoriSampah::all();
        return view('Resident.setoran.create', compact('kategoris'));
    }

    // Menyimpan pengajuan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:kategori_sampah,id',
            'estimasi_berat' => 'nullable|numeric|min:0.1',
            'tanggal_setor' => 'required|date',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
        ]);

        if ($validator->fails()) {
            return redirect()->route('resident.dashboard', ['tab' => 'setor-penghuni'])
                            ->withErrors($validator)
                            ->withInput();
        }

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('setoran_foto', 'public');
        }

        // Simpan setoran
        SetoranSampah::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'estimasi_berat' => $request->estimasi_berat,
            'tanggal_setor' => $request->tanggal_setor,
            'foto' => $fotoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('resident.dashboard', ['tab' => 'setor-penghuni'])
            ->with('success', 'Setoran berhasil diajukan! Menunggu petugas mengambil sampah Anda.');
    }
}