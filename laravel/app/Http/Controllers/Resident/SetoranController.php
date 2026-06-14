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
        $request->merge(['tanggal_setor' => now()->toDateString()]);

        $messages = [
            'kategori_id.required' => 'Kategori sampah wajib dipilih.',
            'kategori_id.exists' => 'Kategori sampah tidak valid.',
            'estimasi_berat.required' => 'Estimasi berat wajib diisi.',
            'estimasi_berat.numeric' => 'Estimasi berat harus berupa angka.',
            'estimasi_berat.min' => 'Berat sampah minimal yang diajukan adalah 1 Kg.',
            'estimasi_berat.max' => 'Berat sampah maksimal yang diajukan adalah 50 Kg.',
            'tanggal_setor.required' => 'Tanggal setor wajib diisi.',
            'tanggal_setor.date' => 'Tanggal setor tidak valid.',
            'foto.required' => 'Foto bukti sampah wajib diunggah.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto maksimal adalah 5 MB.',
        ];

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:kategori_sampah,id',
            'estimasi_berat' => 'required|numeric|min:1|max:50',
            'tanggal_setor' => 'required|date',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

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