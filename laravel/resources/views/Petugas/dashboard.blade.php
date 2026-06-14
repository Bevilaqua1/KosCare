@extends('layouts.petugas')
@section('title', 'Dashboard Petugas')

@section('content')
    <!-- Tab Tugas Hari Ini -->
    <div id="jadwal-petugas" class="tab-content active">
        <!-- Panel Tugas Hari Ini -->
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Tugas Hari Ini & Daftar Setoran Siap Angkut</h3>
                <span class="badge badge-info"
                    style="background: #DBEAFE; color: #1D4ED8; font-weight: 700; padding: 6px 12px; border-radius: 20px;">
                    {{ $setoransHariIni->count() }} kamar
                </span>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alamat Lengkap</th>
                            <th>Info Pemohon & No. Kamar</th>
                            <th>Jenis Sampah & Estimasi</th>
                            <th>Foto</th>
                            <th>Tanggal Jemput</th>
                            <th>Keterangan (Penjemput)</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setoransHariIni as $setoran)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div style="display: flex; align-items: flex-start; gap: 4px; color: var(--primary);">
                                        <i class="fa-solid fa-location-dot" style="margin-top: 4px;"></i>
                                        <div>
                                            <strong>{{ $setoran->user->nama_kos ?? '-' }}</strong><br>
                                            <span class="text-sm"
                                                style="color: var(--text-muted);">{{ $setoran->user->alamat_kos ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $setoran->user->name }}</strong><br>
                                    <span class="text-sm" style="color: var(--text-muted);">
                                        Kamar {{ $setoran->user->no_kamar ?? '-' }}<br>
                                        {{ $setoran->user->no_wa ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $setoran->kategori->nama_kategori ?? '-' }}<br>
                                    <span class="text-sm" style="color: var(--text-muted);">(±
                                        {{ $setoran->estimasi_berat ?? '-' }} Kg)</span>
                                </td>
                                <td>
                                    @if($setoran->foto)
                                        <img src="{{ asset('storage/' . $setoran->foto) }}"
                                            style="width:48px;height:48px;object-fit:cover;border-radius:8px; cursor:pointer;"
                                            onclick="showImagePreview('{{ asset('storage/' . $setoran->foto) }}')">
                                    @else
                                        <div
                                            style="width:48px;height:48px;background:var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ optional($setoran->jadwal)->tanggal ? $setoran->jadwal->tanggal->format('d M Y') : '-' }}</strong><br>
                                    <span class="text-sm" style="color: var(--text-muted);">
                                        {{ optional($setoran->jadwal)->waktu_mulai ? $setoran->jadwal->waktu_mulai->format('H:i') : '-' }}
                                        -
                                        {{ optional($setoran->jadwal)->waktu_selesai ? $setoran->jadwal->waktu_selesai->format('H:i') : '-' }}
                                    </span>
                                </td>
                                <td>{{ optional($setoran->jadwal)->keterangan ?? '-' }}</td>
                                <td>
                                    @if($setoran->status === 'pending')
                                        <form action="{{ route('petugas.tugas.konfirmasi', $setoran->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-small"
                                                style="background: var(--primary); display: flex; align-items: center; gap: 6px;"
                                                onclick="return confirm('Konfirmasi pengangkutan sampah dari {{ $setoran->user->name }}?')">
                                                <i class="fa-solid fa-truck-pickup"></i> Konfirmasi Angkut
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge badge-success">Selesai diangkut</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center;">Tidak ada tugas penjemputan hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Tugas Mendatang -->
        <div class="panel" style="margin-top: 24px;">
            <div class="panel-header">
                <h3 class="panel-title">Tugas Mendatang</h3>
                <span class="badge badge-info"
                    style="background: #E0F2FE; color: #0369A1; font-weight: 700; padding: 6px 12px; border-radius: 20px;">
                    {{ $setoransMendatang->count() }} kamar
                </span>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alamat Lengkap</th>
                            <th>Info Pemohon & No. Kamar</th>
                            <th>Jenis Sampah & Estimasi</th>
                            <th>Foto</th>
                            <th>Tanggal Jemput</th>
                            <th>Keterangan (Penjemput)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setoransMendatang as $setoran)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div style="display: flex; align-items: flex-start; gap: 4px; color: var(--primary);">
                                        <i class="fa-solid fa-location-dot" style="margin-top: 4px;"></i>
                                        <div>
                                            <strong>{{ $setoran->user->nama_kos ?? '-' }}</strong><br>
                                            <span class="text-sm"
                                                style="color: var(--text-muted);">{{ $setoran->user->alamat_kos ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $setoran->user->name }}</strong><br>
                                    <span class="text-sm" style="color: var(--text-muted);">
                                        Kamar {{ $setoran->user->no_kamar ?? '-' }}<br>
                                        {{ $setoran->user->no_wa ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $setoran->kategori->nama_kategori ?? '-' }}<br>
                                    <span class="text-sm" style="color: var(--text-muted);">(±
                                        {{ $setoran->estimasi_berat ?? '-' }} Kg)</span>
                                </td>
                                <td>
                                    @if($setoran->foto)
                                        <img src="{{ asset('storage/' . $setoran->foto) }}"
                                            style="width:48px;height:48px;object-fit:cover;border-radius:8px; cursor:pointer;"
                                            onclick="showImagePreview('{{ asset('storage/' . $setoran->foto) }}')">
                                    @else
                                        <div
                                            style="width:48px;height:48px;background:var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ optional($setoran->jadwal)->tanggal ? $setoran->jadwal->tanggal->format('d M Y') : '-' }}</strong><br>
                                    <span class="text-sm" style="color: var(--text-muted);">
                                        {{ optional($setoran->jadwal)->waktu_mulai ? $setoran->jadwal->waktu_mulai->format('H:i') : '-' }}
                                        -
                                        {{ optional($setoran->jadwal)->waktu_selesai ? $setoran->jadwal->waktu_selesai->format('H:i') : '-' }}
                                    </span>
                                </td>
                                <td>{{ optional($setoran->jadwal)->keterangan ?? '-' }}</td>
                        @empty
                                <tr>
                                    <td colspan="8" style="text-align: center;">Tidak ada tugas penjemputan mendatang.</td>
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
            <div class="panel-header">
                <h3 class="panel-title">Riwayat Pengangkutan Selesai</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu Angkut</th>
                            <th>Lokasi Kamar</th>
                            <th>Jenis Sampah</th>
                            <th>Status Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatAngkut as $setoran)
                            <tr>
                                <td>{{ $setoran->updated_at->format('d M Y, H:i') }}</td>
                                <td><strong>{{ $setoran->user->no_kamar ?? '-' }}</strong><br>{{ $setoran->user->name }}</td>
                                <td>{{ $setoran->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    @if($setoran->status == 'diangkut')
                                        <span class="badge badge-warning">Diperiksa Admin</span>
                                    @elseif($setoran->status == 'selesai')
                                        <span class="badge badge-success">Selesai & Masuk Gudang</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center;">Belum ada riwayat pengangkutan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Image Preview -->
    <div id="imagePreviewModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                                background:rgba(0,0,0,0.8); z-index:2000; justify-content:center; align-items:center;">
        <span
            style="position:absolute; top:20px; right:30px; color:white; font-size:40px; font-weight:bold; cursor:pointer;"
            onclick="closeImagePreview()">&times;</span>
        <img id="imagePreviewSrc" src=""
            style="max-width:90%; max-height:90%; border-radius:8px; box-shadow:0 5px 15px rgba(0,0,0,0.5);">
    </div>
@endsection

@push('scripts')
    <script>
        function showImagePreview(src) {
            document.getElementById('imagePreviewSrc').src = src;
            document.getElementById('imagePreviewModal').style.display = 'flex';
        }

        function closeImagePreview() {
            document.getElementById('imagePreviewModal').style.display = 'none';
        }

        document.getElementById('imagePreviewModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeImagePreview();
            }
        });
    </script>
@endpush