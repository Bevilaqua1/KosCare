<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriReward;
use App\Models\PenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RewardController extends Controller
{
    // Simpan item baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_diperlukan' => 'required|integer|min:1',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
                ->withErrors($validator)
                ->withInput();
        }

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
        $validator = Validator::make($request->all(), [
            'nama_item' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'poin_diperlukan' => 'required|integer|min:1',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
                ->withErrors($validator)
                ->withInput();
        }

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
        $validator = Validator::make($request->all(), ['status' => 'required|in:disetujui,ditolak']);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->status == 'disetujui') {
            // Kurangi stok
            $reward = $penukaran->kategoriReward;
            if ($reward && $reward->stok >= $penukaran->jumlah) {
                $reward->decrement('stok', $penukaran->jumlah);
            } else {
                return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
                    ->with('error', 'Stok tidak mencukupi.');
            }
        }

        $penukaran->update(['status' => $request->status]);

        return redirect()->route('admin.dashboard', ['tab' => 'reward-admin'])
            ->with('success', 'Penukaran berhasil diproses.');
    }
}