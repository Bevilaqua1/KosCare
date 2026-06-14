<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPengangkutan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'keterangan' => 'nullable|string',
            'petugas_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'jadwal-admin'])
                ->withErrors($validator)
                ->withInput();
        }

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
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'keterangan' => 'nullable|string',
            'petugas_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'jadwal-admin'])
                ->withErrors($validator)
                ->withInput();
        }

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