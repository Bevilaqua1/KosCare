<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriReward;
use App\Models\PenukaranPoin;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    // Simpan item baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_diperlukan' => 'required|integer|min:1',
            'stok' => 'required|integer|min:0',
        ]);

        KategoriReward::create($request->only('nama_item', 'deskripsi', 'poin_diperlukan', 'stok'));

        return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
            ->with('success', 'Item reward berhasil ditambahkan.');
    }

    // Data untuk modal edit (JSON)
    public function edit(KategoriReward $reward)
    {
        if (request()->ajax()) {
            return response()->json($reward);
        }
        return redirect()->route('admin.dashboard', ['tab' => 'reward-admin']);
    }

    // Update item
    public function update(Request $request, KategoriReward $reward)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_diperlukan' => 'required|integer|min:1',
            'stok' => 'required|integer|min:0',
        ]);

        $reward->update($request->only('nama_item', 'deskripsi', 'poin_diperlukan', 'stok'));

        return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
            ->with('success', 'Item reward berhasil diperbarui.');
    }

    // Hapus item
    public function destroy(KategoriReward $reward)
    {
        $reward->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
            ->with('success', 'Item reward berhasil dihapus.');
    }

    // Proses penukaran (setujui/tolak)
    public function prosesPenukaran(Request $request, PenukaranPoin $penukaran)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);

        if ($request->status == 'disetujui') {
            // Kurangi stok
            $reward = $penukaran->kategoriReward;
            if ($reward && $reward->stok >= $penukaran->jumlah) {
                $reward->decrement('stok', $penukaran->jumlah);
            } else {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
        }

        $penukaran->update(['status' => $request->status]);

        return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
            ->with('success', 'Penukaran berhasil diproses.');
    }
}