<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    // Tampilkan daftar kategori (untuk modal/ AJAX bisa langsung di dashboard)
    public function index()
    {
        $kategoris = KategoriSampah::all();
        return view('Admin.kategori.index', compact('kategoris'));
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_per_kg' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'kategori-admin'])
                ->withErrors($validator)
                ->withInput();
        }

        KategoriSampah::create($request->only('nama_kategori', 'deskripsi', 'poin_per_kg'));

        return redirect()->route('admin.dashboard', ['tab' => 'kategori-admin'])
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Tampilkan form edit (bisa langsung di modal dengan data JSON)
    public function edit(KategoriSampah $kategori)
    {
    if (request()->ajax()) {
        return response()->json($kategori);
    }
    return view('Admin.kategori.edit', compact('kategori')); // fallback
    }

    // Update kategori
    public function update(Request $request, KategoriSampah $kategori)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_per_kg' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'kategori-admin'])
                ->withErrors($validator)
                ->withInput();
        }

        $kategori->update($request->only('nama_kategori', 'deskripsi', 'poin_per_kg'));

        return redirect()->route('admin.dashboard', ['tab' => 'kategori-admin'])
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // Hapus kategori
    public function destroy(KategoriSampah $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'kategori-admin'])
            ->with('success', 'Kategori berhasil dihapus.');
    }
}