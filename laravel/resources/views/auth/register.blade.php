@extends('layouts.guest')
@section('title', 'Daftar Penghuni')

@section('content')
<div class="auth-screen">
    <div class="auth-visual">
        <div class="auth-logo">
            <img src="{{ asset('image/koscare-logo.jpeg') }}" alt="KosCare Logo">
        </div>
        <h1>Gabung dengan KosCare</h1>
        <p>Jadilah bagian dari komunitas hijau. Tukarkan sampah Anda menjadi poin yang bermanfaat.</p>
    </div>
    <div class="auth-form-container">
        <div class="auth-box">
            <h2>Buat Akun Penghuni</h2>
            <p>Lengkapi data diri Anda di bawah ini.</p>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="auth-grid-two-column">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" 
                               placeholder="Cth: Budi Santoso">
                        @error('name')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="no_kamar">No. Kamar</label>
                        <input id="no_kamar" type="text" class="form-control @error('no_kamar') is-invalid @enderror" 
                               name="no_kamar" value="{{ old('no_kamar') }}" required 
                               placeholder="Cth: B4">
                        @error('no_kamar')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="auth-grid-two-column">
                    <div class="form-group">
                        <label for="nama_kos">Nama Kos</label>
                        <input id="nama_kos" type="text" class="form-control @error('nama_kos') is-invalid @enderror"
                               name="nama_kos" value="{{ old('nama_kos') }}" required 
                               placeholder="Cth: Kos Melati">
                        @error('nama_kos')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat_kos">Alamat Kos</label>
                        <input id="alamat_kos" type="text" class="form-control @error('alamat_kos') is-invalid @enderror"
                               name="alamat_kos" value="{{ old('alamat_kos') }}" required 
                               placeholder="Cth: Jl. Mendalo Indah No.12">
                        @error('alamat_kos')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Aktif</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" 
                           placeholder="budi@kos.id">
                    @error('email')
                        <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password" 
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" class="form-control" 
                           name="password_confirmation" required autocomplete="new-password" 
                           placeholder="Ulangi kata sandi">
                </div>

                <button type="submit" class="btn" style="margin-top: 10px;">
                    Daftar Sekarang <i class="fa-solid fa-user-plus"></i>
                </button>
            </form>
            
            <div class="auth-links">
                Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection