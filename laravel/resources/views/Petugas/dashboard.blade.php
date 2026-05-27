@extends('layouts.petugas')
@section('title', 'Dashboard Petugas')

@section('content')
    <!-- Tab Tugas Hari Ini -->
    <div id="jadwal-petugas" class="tab-content active">
        <!-- Panel Jadwal -->
        <div class="panel" style="margin-bottom: 24px;">
            <div class="panel-header">
                <h3 class="panel-title">Jadwal Penjemputan Saya</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr><th>Tanggal</th><th>Waktu</th><th>Keterangan</th></tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                        <tr>
                            <td>{{ optional($jadwal->tanggal)->format('d M Y') }}</td>
                            <td>{{ optional($jadwal->waktu_mulai)->format('H:i') }} - {{ optional($jadwal->waktu_selesai)->format('H:i') }}</td>
                            <td>{{ $jadwal->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center;">Belum ada jadwal untuk Anda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Daftar Tugas (setoran pending) -->
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Daftar Setoran Siap Angkut</h3>
                <span class="badge badge-info">{{ $setorans->count() }} Kamar</span>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Lokasi Kamar</th>
                            <th>Info Pemohon</th>
                            <th>Kategori & Estimasi</th>
                            <th>Foto</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setorans as $setoran)
                        <tr>
                            <td><div style="font-size:20px; font-weight:800; color:var(--primary-dark);">{{ $setoran->user->no_kamar ?? '?' }}</div></td>
                            <td><strong>{{ $setoran->user->name }}</strong><br><span class="text-sm">{{ $setoran->user->no_kamar ? 'Kamar ' . $setoran->user->no_kamar : '' }}</span></td>
                            <td>{{ $setoran->kategori->nama_kategori ?? '-' }} (± {{ $setoran->estimasi_berat ?? '?' }} Kg)</td>
                            <td>
                                @if($setoran->foto)
                                    <img src="{{ asset('storage/' . $setoran->foto) }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                                @else
                                    <div style="width:48px;height:48px;background:var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('petugas.tugas.konfirmasi', $setoran->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-small" 
                                            onclick="return confirm('Konfirmasi pengangkutan sampah dari {{ $setoran->user->name }}?')">
                                        <i class="fa-solid fa-truck-pickup"></i> Konfirmasi Angkut
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada tugas penjemputan saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Riwayat Angkut -->
    <div id="riwayat-petugas" class="tab-content">
        <div class="panel">
            <div class="panel-header"><h3 class="panel-title">Riwayat Pengangkutan Selesai</h3></div>
            <div class="panel-body" style="text-align: center; color: var(--text-muted); padding: 60px;">
                <i class="fa-solid fa-clock-rotate-left" style="font-size: 48px; color: var(--border); margin-bottom: 16px;"></i>
                <p>Riwayat pengangkutan akan segera tersedia.</p>
            </div>
        </div>
    </div>
@endsection