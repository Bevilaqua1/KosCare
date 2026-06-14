<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPengangkutan;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // Tampilkan daftar jadwal (tidak terpakai karena di dashboard, tapi kita biarkan)
    public function index()
    {
        $jadwals = JadwalPengangkutan::with('petugas')->latest()->get();
        return view('Admin.jadwal.index', compact('jadwals'));
    }

    // Simpan jadwal baru
    public function store(Request $request)
    {
        // Gabungkan tanggal dengan waktu_mulai dan waktu_selesai
        if ($request->filled(['tanggal', 'waktu_mulai'])) {
            $request->merge([
                'waktu_mulai' => $request->tanggal . ' ' . $request->waktu_mulai
            ]);
        }
        if ($request->filled(['tanggal', 'waktu_selesai'])) {
            $request->merge([
                'waktu_selesai' => $request->tanggal . ' ' . $request->waktu_selesai
            ]);
        }

        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date|after:now',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'keterangan' => 'nullable|string',
            'petugas_id' => 'nullable|exists:users,id',
        ]);

        JadwalPengangkutan::create($request->all());

        return redirect()->route('admin.dashboard', ['tab' => 'jadwal-admin'])
            ->with('success', 'Jadwal pengangkutan berhasil ditambahkan.');
    }

    // Mengembalikan data jadwal dalam bentuk JSON untuk modal edit
    public function edit(JadwalPengangkutan $jadwal)
    {
        if (request()->ajax()) {
            return response()->json($jadwal);
        }
        return redirect()->route('admin.dashboard', ['tab' => 'jadwal-admin']);
    }

    // Update jadwal
    public function update(Request $request, JadwalPengangkutan $jadwal)
    {
        // Gabungkan tanggal dengan waktu_mulai dan waktu_selesai
        if ($request->filled(['tanggal', 'waktu_mulai'])) {
            $request->merge([
                'waktu_mulai' => $request->tanggal . ' ' . $request->waktu_mulai
            ]);
        }
        if ($request->filled(['tanggal', 'waktu_selesai'])) {
            $request->merge([
                'waktu_selesai' => $request->tanggal . ' ' . $request->waktu_selesai
            ]);
        }

        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date|after:now',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'keterangan' => 'nullable|string',
            'petugas_id' => 'nullable|exists:users,id',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.dashboard', ['tab' => 'jadwal-admin'])
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    // Hapus jadwal
    public function destroy(JadwalPengangkutan $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'jadwal-admin'])
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}