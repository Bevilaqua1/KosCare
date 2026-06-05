<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('Resident.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_wa' => 'nullable|string|max:20',
            'no_kamar' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'no_wa', 'no_kamar'));

        return redirect()->route('resident.dashboard', ['tab' => 'profile-resident'])
            ->with('success', 'Profil berhasil diperbarui.');
    }
}