@extends('layouts.resident')
@section('title', 'Dashboard Penghuni')

@section('content')
    {{-- Pesan sukses --}}
    @if(session('success'))
    <div style="background: var(--success-bg); color: var(--primary-dark); padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- {{-- ===================== TAB IKHtisar ===================== --}} -->
<div id="dash-penghuni" class="tab-content {{ $activeTab == 'dash-penghuni' ? 'active' : '' }}">
    <div class="card-grid">
        {{-- Kartu Nama Kos --}}
        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-house"></i></div>
            <div class="card-info">
                <h3>Nama Kos</h3>
                <div class="value">{{ Auth::user()->nama_kos ?? 'Belum diisi' }}</div>
            </div>
        </div>
        {{-- Kartu Total Sampah Disetor --}}
        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-box-open"></i></div>
            <div class="card-info">
                <h3>Total Sampah Disetor</h3>
                <div class="value">{{ $totalSetoran }}<span style="font-size:16px; font-weight:600; color:var(--text-muted); margin-left:4px;">Kali</span></div>
            </div>
        </div>
        {{-- Kartu Saldo Poin --}}
        <div class="card">
            <div class="card-icon" style="background:var(--warning-bg); color:#D97706;">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div class="card-info">
                <h3>Saldo Poin</h3>
                <div class="value">{{ $totalPoin }}<span style="font-size:16px; font-weight:600; color:var(--text-muted); margin-left:4px;">Pts</span></div>
            </div>
        </div>
        {{-- Kartu Jadwal Jemput Terdekat --}}
        <div class="card">
            <div class="card-icon" style="background:var(--info-bg); color:var(--info);">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="card-info">
                <h3>Jadwal Jemput Terdekat</h3>
                <div class="value" style="font-size: 24px;">
                    @if($jadwalTerdekat)
                        {{ optional($jadwalTerdekat->tanggal)->format('d M Y') }},
                        {{ optional($jadwalTerdekat->waktu_mulai)->format('H:i') }}
                    @else
                        Belum ada jadwal
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- ===================== TAB AJUKAN SETORAN ===================== --}}
    <div id="setor-penghuni" class="tab-content {{ $activeTab == 'setor-penghuni' ? 'active' : '' }}">
        <div class="panel" style="max-width: 700px; margin: 0 auto;">
            <div class="panel-header">
                <h3 class="panel-title">Form Ajukan Setoran</h3>
            </div>
            <div class="panel-body">
                @if(session('success'))
                    <div style="background: var(--success-bg); color: var(--primary-dark); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background: #fee2e2; color: var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        <i class="fa-solid fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('resident.setoran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="kategori_id">Kategori Dominan Sampah</label>
                        <select name="kategori_id" id="kategori_id" class="form-control" required>
                            <option value="" disabled selected>Pilih jenis sampah...</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="estimasi_berat">Estimasi Berat Total (Opsional)</label>
                        <div style="position: relative;">
                            <input type="number" name="estimasi_berat" id="estimasi_berat" class="form-control" 
                                placeholder="0.0" step="0.1" min="0" value="{{ old('estimasi_berat') }}">
                            <span style="position: absolute; right: 16px; top: 12px; color: var(--text-light); font-weight: 600;">Kg</span>
                        </div>
                        @error('estimasi_berat')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_setor">Tanggal Penyetoran</label>
                        <input type="date" name="tanggal_setor" id="tanggal_setor" class="form-control" 
                            value="{{ old('tanggal_setor', date('Y-m-d')) }}" required>
                        @error('tanggal_setor')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-top: 32px;">
                        <label>Foto Bukti Sampah (Wajib)</label>
                        <p class="text-sm mb-4">Foto ini akan membantu petugas mempersiapkan alat angkut yang sesuai.</p>
                        <label for="foto" class="wireframe-box" style="cursor: pointer;">
                            <div class="icon-upload"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                            <div><strong style="color: var(--primary);">Klik untuk unggah</strong> atau tarik & lepas berkas</div>
                            <div class="text-sm">Mendukung format JPG, PNG (Maksimal 5MB)</div>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/jpeg, image/png" 
                            style="display: none;" onchange="displayFileName(this)">
                        <div id="file-name-display" class="text-sm" style="margin-top: 8px;"></div>
                        @error('foto')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <hr style="border: 0; border-top: 1px solid var(--border); margin: 32px 0;">
                    <div style="display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="reset" class="btn btn-outline" style="width: auto;">Batal</button>
                        <button type="submit" class="btn" style="width: auto;">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===================== TAB JADWAL ANGKUT ===================== --}}
    <div id="jadwal-penghuni" class="tab-content {{ $activeTab == 'jadwal-penghuni' ? 'active' : '' }}">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Jadwal Operasional Bank Sampah</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                        <tr>
                            <td>{{ optional($jadwal->tanggal)->format('d M Y') }}</td>
                            <td>{{ optional($jadwal->waktu_mulai)->format('H:i') }} - {{ optional($jadwal->waktu_selesai)->format('H:i') }}</td>
                            <td>
                                <div class="td-user">
                                    <div class="td-user-icon"><i class="fa-solid fa-user"></i></div>
                                    {{ optional($jadwal->petugas)->name ?? 'Belum ditugaskan' }}
                                </div>
                            </td>
                            <td>{{ $jadwal->keterangan ?? 'Seluruh Area' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada jadwal penjemputan untuk setoran Anda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== TAB TUKAR POIN (REWARD) ===================== --}}
    <div id="reward-resident" class="tab-content {{ $activeTab == 'reward-resident' ? 'active' : '' }}">
        <h3>Katalog Reward</h3>
        <div class="card-grid">
            @forelse($rewards as $item)
            <div class="card" style="flex-direction: column; align-items: flex-start;">
                <div class="card-icon"><i class="fa-solid fa-gift"></i></div>
                <div class="card-info" style="width:100%;">
                    <h3>{{ $item->nama_item }}</h3>
                    <p class="text-sm">{{ $item->deskripsi }}</p>
                    <div class="value" style="font-size:20px;">{{ $item->poin_diperlukan }} Pts</div>
                    <form action="{{ route('resident.reward.tukar') }}" method="POST" style="margin-top:12px;">
                        @csrf
                        <input type="hidden" name="reward_id" value="{{ $item->id }}">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label>Jumlah:</label>
                            <input type="number" name="jumlah" value="1" min="1" max="{{ $item->stok }}" style="width:60px;" class="form-control">
                            <button type="submit" class="btn btn-small">Tukar</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <p style="text-align:center; width:100%;">Belum ada reward tersedia.</p>
            @endforelse
        </div>

        <h3 style="margin-top:24px;">Riwayat Penukaran</h3>
        <div class="table-responsive">
            <table>
                <thead><tr><th>Item</th><th>Jumlah</th><th>Poin</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @forelse($riwayatPenukaran as $r)
                    <tr>
                        <td>{{ $r->kategoriReward->nama_item }}</td>
                        <td>{{ $r->jumlah }}</td>
                        <td>{{ $r->total_poin }}</td>
                        <td>
                            @if($r->status == 'pending') <span class="badge badge-warning">Menunggu</span>
                            @elseif($r->status == 'disetujui') <span class="badge badge-success">Disetujui</span>
                            @else <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>{{ $r->tanggal_penukaran->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;">Belum ada penukaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===================== TAB ARTIKEL EDUKASI ===================== --}}
    <div id="artikel-resident" class="tab-content {{ $activeTab == 'artikel-resident' ? 'active' : '' }}">
        <div class="card-grid">
            @forelse($artikels as $artikel)
            <a href="{{ route('resident.artikel.show', $artikel->id) }}" style="text-decoration:none;">
                <div class="card" style="flex-direction: column; align-items: flex-start; cursor: pointer;">
                    @if($artikel->gambar)
                        <img src="{{ asset('storage/' . $artikel->gambar) }}" style="width:100%; height:160px; object-fit:cover; border-radius:12px 12px 0 0;">
                    @endif
                    <div style="padding: 16px;">
                        <h3>{{ $artikel->judul }}</h3>
                        <p class="text-sm">{{ \Str::limit(strip_tags($artikel->isi), 120) }}</p>
                        <div class="text-sm" style="margin-top: 8px;">
                            {{ optional($artikel->tanggal_terbit)->format('d M Y') ?? $artikel->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <p style="text-align:center; width:100%;">Belum ada artikel edukasi.</p>
            @endforelse
        </div>
    </div>

    {{-- ===================== TAB RIWAYAT TRANSAKSI ===================== --}}
    <div id="riwayat-penghuni" class="tab-content {{ $activeTab == 'riwayat-penghuni' ? 'active' : '' }}">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Riwayat Transaksi Setoran</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal Setor</th>
                            <th>Kategori Sampah</th>
                            <th>Berat Tervalidasi</th>
                            <th>Poin Diterima</th>
                            <th>Status</th>
                            <th>Jadwal Jemput</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatSetoran as $setoran)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($setoran->tanggal_setor)->format('d M Y, H:i') }}</td>
                            <td><strong>{{ $setoran->kategori->nama_kategori ?? '-' }}</strong></td>
                            <td>
                                @if($setoran->berat_aktual)
                                    {{ $setoran->berat_aktual }} Kg
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($setoran->status == 'selesai')
                                    <span style="color: var(--primary); font-weight: 700;">+ {{ $setoran->poin_didapat }} Pts</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($setoran->status == 'pending')
                                    <span class="badge badge-warning"><i class="fa-regular fa-clock"></i> Menunggu Angkut</span>
                                @elseif($setoran->status == 'diangkut')
                                    <span class="badge badge-info"><i class="fa-solid fa-truck"></i> Sedang Diproses</span>
                                @elseif($setoran->status == 'selesai')
                                    <span class="badge badge-success"><i class="fa-solid fa-check"></i> Selesai</span>
                                @elseif($setoran->status == 'ditolak')
                                    <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($setoran->jadwal)
                                    {{ $setoran->jadwal->tanggal->format('d M Y') }}, {{ $setoran->jadwal->waktu_mulai->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Belum ada setoran. Ajukan sekarang!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== TAB PROFIL SAYA ===================== --}}
    <div id="profile-resident" class="tab-content {{ $activeTab == 'profile-resident' ? 'active' : '' }}">
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
                        <input type="text" name="name" class="form-control" 
                            value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor WhatsApp</label>
                        <input type="text" name="no_wa" class="form-control" 
                            value="{{ old('no_wa', Auth::user()->no_wa) }}">
                        @error('no_wa')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor Kamar</label>
                        <input type="text" name="no_kamar" class="form-control" 
                            value="{{ old('no_kamar', Auth::user()->no_kamar) }}">
                        @error('no_kamar')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Kos</label>
                        <input type="text" name="nama_kos" class="form-control" 
                            value="{{ old('nama_kos', Auth::user()->nama_kos) }}" required>
                        @error('nama_kos')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat Kos</label>
                        <input type="text" name="alamat_kos" class="form-control" 
                            value="{{ old('alamat_kos', Auth::user()->alamat_kos) }}" required>
                        @error('alamat_kos')
                            <span class="text-sm" style="color: var(--danger);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn">
                            <i class="fa-solid fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function displayFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            display.innerText = 'File dipilih: ' + input.files[0].name;
        } else {
            display.innerText = '';
        }
    }
</script>
@endpush