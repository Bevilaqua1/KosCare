@extends('layouts.resident')
@section('title', 'Artikel Edukasi')

@section('content')
<div class="card-grid">
    @forelse($artikels as $artikel)
    <a href="{{ route('resident.artikel.show', $artikel->id) }}" style="text-decoration:none;">
        <div class="card" style="flex-direction: column; align-items: flex-start; cursor: pointer;">
            @if($artikel->gambar)
                <img src="{{ asset('storage/' . $artikel->gambar) }}" style="width:100%; height:160px; object-fit:cover; border-radius:12px 12px 0 0;">
            @endif
            <div style="padding: 16px;">
                <h3>{{ $artikel->judul }}</h3>
                <p class="text-sm">{{ Str::limit(strip_tags($artikel->isi), 120) }}</p>
                <div class="text-sm" style="margin-top: 8px;">
                    {{ optional($artikel->tanggal_terbit)->format('d M Y') ?? $artikel->created_at->format('d M Y') }}
                </div>
            </div>
        </div>
    </a>
    @empty
    <p>Belum ada artikel.</p>
    @endforelse
</div>
@endsection