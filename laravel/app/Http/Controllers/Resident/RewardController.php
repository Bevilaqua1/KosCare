<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\KategoriReward;
use App\Models\PenukaranPoin;
use App\Models\SetoranSampah;  
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
        ], [
            'reward_id.required' => 'Reward wajib dipilih.',
            'reward_id.exists' => 'Item reward yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah penukaran wajib diisi.',
            'jumlah.integer' => 'Jumlah penukaran harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah penukaran minimal adalah 1.',
        ]);

         // ... validasi

        $reward = KategoriReward::findOrFail($request->reward_id);
        $user = Auth::user();

        // Hitung saldo poin sebenarnya
        $totalPoinDiterima = SetoranSampah::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->sum('poin_didapat');
        $totalPoinDipakai = PenukaranPoin::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum('total_poin');
        $saldoPoin = max(0, $totalPoinDiterima - $totalPoinDipakai);

        $poinDibutuhkan = $reward->poin_diperlukan * $request->jumlah;

        // Cek saldo mencukupi
        if ($saldoPoin < $poinDibutuhkan) {
            return back()->with('error', 'Poin Anda tidak mencukupi.');
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