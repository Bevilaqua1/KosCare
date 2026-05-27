@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div class="auth-screen">
    <div class="auth-visual">
        <h1><i class="fa-solid fa-leaf"></i> KosCare</h1>
        <p>Platform pintar untuk mengelola bank sampah di lingkungan kos Anda. Mulai langkah kecil untuk bumi yang lebih bersih.</p>
    </div>
    <div class="auth-form-container">
        <div class="auth-box">
            <h2>Selamat Datang Kembali</h2>
            <p>Silakan masuk untuk melanjutkan aktivitas Anda.</p>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                    @error('email')
                        <span style="color: var(--danger); font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="current-password">
                    @error('password')
                        <span style="color: var(--danger); font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="btn" style="margin-top: 10px;">
                    Masuk ke Dashboard <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
            
            <div class="auth-links">
                Baru di KosCare? <a href="{{ route('register') }}">Daftar sebagai Penghuni</a>
            </div>
        </div>
    </div>
</div>
@endsection