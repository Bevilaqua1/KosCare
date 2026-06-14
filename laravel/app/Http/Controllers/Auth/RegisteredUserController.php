<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'no_kamar' => ['required', 'string', 'max:100'],
        'nama_kos' => ['required', 'string', 'max:255'],
        'alamat_kos' => ['required', 'string', 'max:255'],
        'no_wa' => ['required', 'string', 'max:20'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'resident',
        'no_kamar' => $request->no_kamar,
        'nama_kos' => $request->nama_kos,
        'alamat_kos' => $request->alamat_kos,
        'no_wa' => $request->no_wa,
    ]);

    event(new Registered($user));

    // Kembalikan ke halaman login dengan pesan sukses
    return redirect()->route('login')->with('success', 'Pendaftaran akun berhasil! Silakan masuk dengan email dan kata sandi Anda.');
}
}
