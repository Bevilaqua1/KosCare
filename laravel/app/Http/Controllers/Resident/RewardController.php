<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\KategoriReward;
use App\Models\PenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = KategoriReward::where('stok', '>', 0)->get();
        $riwayat = PenukaranPoin::with('kategoriReward')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();
        return view('Resident.reward.index', compact('rewards', 'riwayat')); // nanti bisa langsung di dashboard
    }

    public function store(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|exists:kategori_reward,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $reward = KategoriReward::findOrFail($request->reward_id);
        $user = Auth::user();
        $totalPoin = $user->setoranSampah()->sum('poin_didapat');

        $poinDibutuhkan = $reward->poin_diperlukan * $request->jumlah;

        if ($totalPoin < $poinDibutuhkan) {
            return back()->with('error', 'Poin Anda tidak mencukupi.');
        }

        if ($reward->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        PenukaranPoin::create([
            'user_id' => $user->id,
            'kategori_reward_id' => $reward->id,
            'jumlah' => $request->jumlah,
            'total_poin' => $poinDibutuhkan,
            'status' => 'pending',
            'tanggal_penukaran' => now(),
        ]);

        return redirect()->route('resident.dashboard', ['tab' => 'reward-resident'])
            ->with('success', 'Penukaran berhasil diajukan. Menunggu konfirmasi admin.');
    }

    public function riwayat()
    {
        $riwayat = PenukaranPoin::with('kategoriReward')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();
        return view('Resident.reward.riwayat', compact('riwayat'));
    }
}   