@extends('layouts.resident')
@section('title', $artikel->judul)

@section('content')
<div class="panel">
    @if($artikel->gambar)
        <img src="{{ asset('storage/' . $artikel->gambar) }}" style="width:100%; max-height:300px; object-fit:cover; border-radius:12px 12px 0 0;">
    @endif
    <div class="panel-body">
        <h2>{{ $artikel->judul }}</h2>
        <div class="text-sm" style="margin: 8px 0 24px;">
            {{ optional($artikel->tanggal_terbit)->format('d M Y') ?? $artikel->created_at->format('d M Y') }}
        </div>
        <div style="line-height: 1.8;">
            {!! nl2br(e($artikel->isi)) !!}
        </div>
    </div>
</div>
@endsection