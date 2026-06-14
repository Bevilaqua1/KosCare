<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('Resident.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'no_wa' => 'nullable|string|max:20',
            'no_kamar' => 'nullable|string|max:100',
            'nama_kos' => 'required|string|max:255',
            'alamat_kos' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('resident.dashboard', ['tab' => 'profile-resident'])
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $user->update($request->only('name', 'no_wa', 'no_kamar', 'nama_kos', 'alamat_kos'));

        return redirect()->route('resident.dashboard', ['tab' => 'profile-resident'])
            ->with('success', 'Profil berhasil diperbarui.');
    }
}