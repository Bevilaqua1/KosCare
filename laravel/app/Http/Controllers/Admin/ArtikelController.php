<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArtikelEdukasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = ArtikelEdukasi::latest('tanggal_terbit')->get();
        return view('Admin.artikel.index', compact('artikels'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_terbit' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'artikel-admin'])
                ->withErrors($validator)
                ->withInput();
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikel', 'public');
        }

        ArtikelEdukasi::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'gambar' => $gambarPath,
            'tanggal_terbit' => $request->tanggal_terbit,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'artikel-admin'])
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(ArtikelEdukasi $artikel)
    {
        if (request()->ajax()) {
            // Ubah format tanggal_terbit menjadi Y-m-d untuk input type="date"
            $artikel->tanggal_terbit = optional($artikel->tanggal_terbit)->format('Y-m-d');
            return response()->json($artikel);
        }
        return redirect()->route('admin.dashboard', ['tab' => 'artikel-admin']);
    }

    public function update(Request $request, ArtikelEdukasi $artikel)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_terbit' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'artikel-admin'])
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only('judul', 'isi', 'tanggal_terbit');
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('artikel', 'public');
        }

        $artikel->update($data);

        return redirect()->route('admin.dashboard', ['tab' => 'artikel-admin'])
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(ArtikelEdukasi $artikel)
    {
        $artikel->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'artikel-admin'])
            ->with('success', 'Artikel berhasil dihapus.');
    }
}