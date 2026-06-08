<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('Admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['resident', 'petugas', 'admin'])],
            'no_wa' => 'nullable|string|max:20',
            'no_kamar' => 'nullable|string|max:50',
            'nama_kos' => 'nullable|string|max:100',
            'alamat_kos' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_wa' => $request->no_wa,
            'no_kamar' => $request->no_kamar,
            'nama_kos' => $request->nama_kos,
            'alamat_kos' => $request->alamat_kos,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'pengguna-admin'])
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        if (request()->ajax()) {
            return response()->json($user);
        }
        return redirect()->route('admin.dashboard', ['tab' => 'pengguna-admin']);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['resident', 'petugas', 'admin'])],
            'no_wa' => 'nullable|string|max:20',
            'no_kamar' => 'nullable|string|max:50',
            'nama_kos' => 'nullable|string|max:100',
            'alamat_kos' => 'nullable|string|max:255',
        ]);

        $data = $request->only('name', 'email', 'role', 'no_wa', 'no_kamar', 'nama_kos', 'alamat_kos');

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.dashboard', ['tab' => 'pengguna-admin'])
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.dashboard', ['tab' => 'pengguna-admin'])
                ->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'pengguna-admin'])
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}