@extends('layouts.resident')
@section('title', 'Edit Profil')

@section('content')
<div class="panel" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h3 class="panel-title">Edit Profil Saya</h3>
    </div>
    <div class="panel-body">
        <form action="{{ route('resident.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <span class="text-sm" style="color:var(--danger);">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="text" name="no_wa" class="form-control" value="{{ old('no_wa', $user->no_wa) }}">
                @error('no_wa') <span class="text-sm" style="color:var(--danger);">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Nomor Kamar</label>
                <input type="text" name="no_kamar" class="form-control" value="{{ old('no_kamar', $user->no_kamar) }}">
                @error('no_kamar') <span class="text-sm" style="color:var(--danger);">{{ $message }}</span> @enderror
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection