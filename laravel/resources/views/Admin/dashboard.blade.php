@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    {{-- Pesan sukses --}}
    @if(session('success'))
    <div style="background: var(--success-bg); color: var(--primary-dark); padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div style="background: #F8D7DA; color: #842029; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fa-solid fa-exclamation-circle"></i> Terjadi kesalahan:
        <ul style="margin: 12px 0 0 18px; padding: 0; list-style: disc;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tab: Ikhtisar Utama -->
    <div id="dash-admin" class="tab-content {{ $activeTab == 'dash-admin' ? 'active' : '' }}">
        <div class="card-grid">
            <div class="card">
                <div class="card-icon" style="background:var(--warning-bg); color:#D97706;">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div class="card-info">
                    <h3>Menunggu Validasi</h3>
                    <div class="value">{{ $pendingSetoran }} <span class="text-sm">Pengajuan</span></div>
                </div>
            </div>
            <div class="card">
                <div class="card-icon" style="background:var(--info-bg); color:#1D4ED8;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="card-info">
                    <h3>Total Pengguna Aktif</h3>
                    <div class="value">{{ $totalPengguna }} <span class="text-sm">Orang</span></div>
                </div>
            </div>
            <div class="card">
                <div class="card-icon" style="background:var(--success-bg); color:var(--primary-dark);">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div class="card-info">
                    <h3>Total Sampah Terkumpul</h3>
                    <div class="value">{{ number_format($totalSampahTerkumpul, 1) }} <span class="text-sm">Kg</span></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Perlu Tindakan Cepat (Validasi Baru)</h3>
            </div>
            <div class="table-responsive">
                <table style="margin: 0; width: 100%;">
                    <tbody>
                        @forelse($setoransDiangkut->take(1) as $setoran)
                        <tr>
                            <td><strong>#{{ $setoran->id }}</strong><br><span class="text-sm">{{ optional($setoran->user)->no_kamar ?? '-' }}</span></td>
                            <td>{{ $setoran->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ optional($setoran->tanggal_setor)->format('d M Y') ?? '-' }}</td>
                            <td style="text-align: right;">
                                <button class="btn btn-small"
                                    onclick="switchTab('admin', 'validasi-admin', document.querySelectorAll('#app-admin .menu-item')[1])">
                                    Cek Detail <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Tidak ada setoran baru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Validasi Setoran -->
    <div id="validasi-admin" class="tab-content {{ $activeTab == 'validasi-admin' ? 'active' : '' }}">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Proses Validasi Setoran</h3>
                <div class="text-sm">Timbang sampah aktual sebelum menyetujui poin.</div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID & Penghuni</th>
                            <th>Kategori</th>
                            <th>Foto</th>
                            <th>Berat Aktual (Kg)</th>
                            <th>Aksi Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setoransDiangkut as $setoran)
                        <tr>
                            <td>
                                <strong>#{{ $setoran->id }}</strong><br>
                                <span class="text-sm">{{ optional($setoran->user)->no_kamar ?? '-' }} ({{ optional($setoran->user)->name ?? 'Tidak Dikenal' }})</span>
                            </td>
                            <td><span class="badge badge-info">{{ $setoran->kategori->nama_kategori ?? '-' }}</span></td>
                            <td>
                                @if($setoran->foto)
                                    <img src="{{ asset('image/koscare-logo.jpeg') }}" alt="Foto" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div style="width:48px;height:48px;background:var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <form id="verifikasi-form-{{ $setoran->id }}" action="{{ route('admin.setoran.verifikasi', $setoran->id) }}" method="POST" class="form-verifikasi" style="display: flex; align-items: center; gap: 8px;">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="berat_aktual" class="form-control" 
                                        style="width:80px; padding:8px;" step="0.1" required>
                                    <span>Kg</span>
                                </form>
                            </td>
                            <td class="action-buttons">
                                <button type="submit" class="btn btn-small" form="verifikasi-form-{{ $setoran->id }}" style="background: var(--primary); margin-right: 8px;">
                                    <i class="fa-solid fa-check"></i> Setujui
                                </button>
                                <form action="{{ route('admin.setoran.tolak', $setoran->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-small btn-danger btn-icon" title="Tolak">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada setoran untuk divalidasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Kelola Kategori -->
    <div id="kategori-admin" class="tab-content {{ $activeTab == 'kategori-admin' ? 'active' : '' }}">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Kelola Kategori & Poin</h3>
                <button class="btn btn-small" onclick="openKategoriModal()">
                    <i class="fa-solid fa-plus"></i> Kategori Baru
                </button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Keterangan / Panduan</th>
                            <th>Nilai Konversi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $kat)
                        <tr>
                            <td><strong>{{ $kat->nama_kategori }}</strong></td>
                            <td>{{ $kat->deskripsi ?? '-' }}</td>
                            <td><span class="badge badge-success">{{ $kat->poin_per_kg }} Poin / Kg</span></td>
                            <td class="action-buttons">
                                <button class="btn btn-small btn-outline btn-icon" onclick="editKategori({{ $kat->id }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.kategori.destroy', $kat->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-small btn-danger btn-icon" 
                                            onclick="return confirm('Yakin hapus kategori ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada data kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Kelola Jadwal -->
    <div id="jadwal-admin" class="tab-content {{ $activeTab == 'jadwal-admin' ? 'active' : '' }}">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Kelola Jadwal</h3>
                <div class="text-sm">Jadwalkan penjemputan untuk setoran yang masih pending.</div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID & Penghuni</th>
                            <th>Kategori</th>
                            <th>Estimasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setoransPending as $setoran)
                        <tr>
                            <td>
                                <strong>#{{ $setoran->id }}</strong><br>
                                <span class="text-sm">{{ optional($setoran->user)->no_kamar ?? '-' }} ({{ optional($setoran->user)->name ?? 'Tidak Dikenal' }})</span>
                            </td>
                            <td><span class="badge badge-info">{{ $setoran->kategori->nama_kategori ?? '-' }}</span></td>
                            <td>{{ $setoran->estimasi_berat ?? '-' }} Kg</td>
                            <td class="action-buttons">
                                @if(!$setoran->jadwal_id)
                                    <button class="btn btn-small btn-outline" onclick="openJadwalSetoranModal({{ $setoran->id }})">
                                        <i class="fa-solid fa-calendar-plus"></i> Jadwalkan
                                    </button>
                                @else
                                    <span class="badge badge-info">Sudah Dijadwalkan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Tidak ada setoran pending.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Kelola Reward & Penukaran -->
        <div id="reward-admin" class="tab-content {{ $activeTab == 'reward-admin' ? 'active' : '' }}">
            <!-- Panel Katalog Reward -->
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">Katalog Reward</h3>
                    <button class="btn btn-small" onclick="openRewardModal()">
                        <i class="fa-solid fa-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>Item</th><th>Poin</th><th>Stok</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse($rewards as $item)
                            <tr>
                                <td><strong>{{ $item->nama_item }}</strong><br><span class="text-sm">{{ $item->deskripsi ?? '-' }}</span></td>
                                <td>{{ $item->poin_diperlukan }} Poin</td>
                                <td>{{ $item->stok }}</td>
                                <td class="action-buttons">
                                    <button class="btn btn-small btn-outline btn-icon" onclick="editReward({{ $item->id }})">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.reward.destroy', $item->id) }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-small btn-danger btn-icon" onclick="return confirm('Yakin hapus?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Belum ada item reward.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

               <!-- Tab Validasi Penukaran -->
            <div class="panel" style="margin-top: 24px;">
                <div class="panel-header">
                    <h3 class="panel-title">Validasi Penukaran Poin</h3>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>Penghuni</th><th>Item</th><th>Jumlah</th><th>Total Poin</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse($penukarans as $p)
                            <tr>
                                <td>{{ $p->user->name }}</td>
                                <td>{{ $p->kategoriReward->nama_item }}</td>
                                <td>{{ $p->jumlah }}</td>
                                <td>{{ $p->total_poin }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <form action="{{ route('admin.reward.proses-penukaran', $p->id) }}" method="POST" style="display:inline-flex; gap:8px; margin:0;">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button class="btn btn-small" style="background: var(--primary);">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.reward.proses-penukaran', $p->id) }}" method="POST" style="display:inline-flex; margin:0;">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button class="btn btn-small btn-danger btn-icon" title="Tolak"><i class="fa-solid fa-xmark"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Tidak ada penukaran menunggu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- Modal Reward -->
    <div id="rewardModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
                background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
        <div style="background:white; border-radius:16px; width:90%; max-width:540px; padding:32px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-top:0;" id="rewardModalTitle">Tambah Item Reward</h3>
            <form id="rewardForm" method="POST">
                @csrf
                <div id="rewardMethodField"></div>
                <div class="form-group">
                    <label>Nama Item</label>
                    <input type="text" name="nama_item" id="reward_nama_item" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="reward_deskripsi" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Poin Diperlukan</label>
                    <input type="number" name="poin_diperlukan" id="reward_poin_diperlukan" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" id="reward_stok" class="form-control" min="0" required>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
                    <button type="button" class="btn btn-outline" style="width:auto;" onclick="closeRewardModal()">Batal</button>
                    <button type="submit" class="btn" style="width:auto;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

<!-- Tab: Kelola Artikel -->
    <div id="artikel-admin" class="tab-content {{ $activeTab == 'artikel-admin' ? 'active' : '' }}">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Kelola Artikel Edukasi</h3>
                <button class="btn btn-small" onclick="openArtikelModal()">
                    <i class="fa-solid fa-plus"></i> Tulis Artikel
                </button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr><th>Judul / Isi</th><th>Tanggal</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($artikels as $artikel)
                        <tr>
                            <td>
                                <strong>{{ $artikel->judul }}</strong><br>
                                <span class="text-sm">{{ Str::limit(strip_tags($artikel->isi), 100) }}</span>
                            </td>
                            <td>{{ optional($artikel->tanggal_terbit)->format('d M Y') ?? $artikel->created_at->format('d M Y') }}</td>
                            <td class="action-buttons">
                                <button class="btn btn-small btn-outline btn-icon" onclick="editArtikel({{ $artikel->id }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.artikel.destroy', $artikel->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-small btn-danger btn-icon" onclick="return confirm('Yakin hapus?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align: center;">Belum ada artikel.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Tab: Kelola Pengguna -->
    <div id="pengguna-admin" class="tab-content {{ $activeTab == 'pengguna-admin' ? 'active' : '' }}">
        {{-- ... --}}
    </div>

 <!-- Tab: Laporan -->
    <div id="laporan-admin" class="tab-content {{ $activeTab == 'laporan-admin' ? 'active' : '' }}">
        <div class="card-grid">
            <div class="card">
                <div class="card-icon" style="background:var(--info-bg); color:#1D4ED8;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="card-info">
                    <h3>Total Sampah Masuk</h3>
                    <div class="value">{{ number_format($totalSampahTerkumpul, 1) }}<span style="font-size:16px; font-weight:600; color:var(--text-muted); margin-left:4px;">Kg</span></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header"><h3 class="panel-title">Grafik Volume Sampah per Bulan</h3></div>
            <div class="panel-body">
                <div class="chart-container">
                    <canvas id="barChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 24px;">
            <div class="panel-header"><h3 class="panel-title">Komposisi Kategori Sampah</h3></div>
            <div class="panel-body">
                <div class="chart-container">
                    <canvas id="pieChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
<!-- ===================== MODAL KATEGORI ===================== -->
    <div id="kategoriModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
        <div style="background:white; border-radius:16px; width:90%; max-width:500px; padding:32px; 
                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-top:0;" id="modalTitle">Tambah Kategori</h3>
            <form id="kategoriForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Poin per Kg</label>
                    <input type="number" name="poin_per_kg" id="poin_per_kg" class="form-control" required>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
                    <button type="button" class="btn btn-outline" style="width:auto;" onclick="closeKategoriModal()">Batal</button>
                    <button type="submit" class="btn" style="width:auto;">Simpan</button>
                </div>
            </form>
        </div>
    </div>


        <!-- Modal Jadwal Setoran -->
    <div id="jadwalSetoranModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                background:rgba(0,0,0,0.5); z-index:1001; justify-content:center; align-items:center;">
        <div style="background:white; border-radius:16px; width:90%; max-width:500px; padding:32px; 
                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-top:0;" id="jadwalSetoranModalTitle">Jadwalkan Penjemputan</h3>
            <form id="jadwalSetoranForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Tanggal Jemput</label>
                    <input type="date" name="tanggal_jemput" id="tanggal_jemput" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai_jemput" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai_jemput" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Petugas</label>
                    <select name="petugas_id" id="petugas_id_jemput" class="form-control" required>
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($petugasList as $petugas)
                            <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" id="keterangan_jemput" class="form-control" rows="2"></textarea>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
                    <button type="button" class="btn btn-outline" style="width:auto;" onclick="closeJadwalSetoranModal()">Batal</button>
                    <button type="submit" class="btn" style="width:auto;">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Jadwal -->
    <div id="jadwalModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
        <div style="background:white; border-radius:16px; width:90%; max-width:500px; padding:32px; 
                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-top:0;" id="jadwalModalTitle">Tambah Jadwal</h3>
            <form id="jadwalForm" method="POST">
                @csrf
                <div id="jadwalMethodField"></div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="jadwal_tanggal" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" id="jadwal_waktu_mulai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" id="jadwal_waktu_selesai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Petugas</label>
                    <select name="petugas_id" id="jadwal_petugas_id" class="form-control">
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($petugasList as $petugas)
                            <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" id="jadwal_keterangan" class="form-control" rows="2"></textarea>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
                    <button type="button" class="btn btn-outline" style="width:auto;" onclick="closeJadwalModal()">Batal</button>
                    <button type="submit" class="btn" style="width:auto;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Artikel -->
    <div id="artikelModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
        <div style="background:white; border-radius:16px; width:90%; max-width:600px; padding:32px; 
                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-top:0;" id="artikelModalTitle">Tulis Artikel</h3>
            <form id="artikelForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="artikelMethodField"></div>
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="artikel_judul" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Isi / Konten</label>
                    <textarea name="isi" id="artikel_isi" class="form-control" rows="8" required></textarea>
                </div>
                <div class="form-group">
                    <label>Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" id="artikel_tanggal" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Gambar (opsional)</label>
                    <input type="file" name="gambar" id="artikel_gambar" class="form-control" accept="image/*">
                </div>
                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
                    <button type="button" class="btn btn-outline" style="width:auto;" onclick="closeArtikelModal()">Batal</button>
                    <button type="submit" class="btn" style="width:auto;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
// ----- Modal Kategori -----
    function openKategoriModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Kategori';
        document.getElementById('kategoriForm').action = "{{ route('admin.kategori.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('nama_kategori').value = '';
        document.getElementById('deskripsi').value = '';
        document.getElementById('poin_per_kg').value = '';
        document.getElementById('kategoriModal').style.display = 'flex';
    }

    async function editKategori(id) {
        try {
            const response = await fetch(`/admin/kategori/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Gagal mengambil data');
            const data = await response.json();

            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            document.getElementById('kategoriForm').action = `/admin/kategori/${data.id}`;
            document.getElementById('methodField').innerHTML = `@csrf @method('PUT')`;
            document.getElementById('nama_kategori').value = data.nama_kategori;
            document.getElementById('deskripsi').value = data.deskripsi || '';
            document.getElementById('poin_per_kg').value = data.poin_per_kg;
            document.getElementById('kategoriModal').style.display = 'flex';
        } catch (error) {
            alert('Terjadi kesalahan: ' + error.message);
        }
    }

    function closeKategoriModal() {
        document.getElementById('kategoriModal').style.display = 'none';
    }

    document.getElementById('kategoriModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeKategoriModal();
        }
    });

// ----- Modal Jadwal -----
    function openJadwalModal() {
        document.getElementById('jadwalModalTitle').innerText = 'Tambah Jadwal';
        document.getElementById('jadwalForm').action = "{{ route('admin.jadwal.store') }}";
        document.getElementById('jadwalMethodField').innerHTML = '';
        document.getElementById('jadwal_tanggal').value = '';
        document.getElementById('jadwal_waktu_mulai').value = '';
        document.getElementById('jadwal_waktu_selesai').value = '';
        document.getElementById('jadwal_petugas_id').value = '';
        document.getElementById('jadwal_keterangan').value = '';
        document.getElementById('jadwalModal').style.display = 'flex';
    }

    async function editJadwal(id) {
        try {
            const response = await fetch(`/admin/jadwal/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Gagal mengambil data');
            const data = await response.json();

            document.getElementById('jadwalModalTitle').innerText = 'Edit Jadwal';
            document.getElementById('jadwalForm').action = `/admin/jadwal/${data.id}`;
            document.getElementById('jadwalMethodField').innerHTML = `@csrf @method('PUT')`;

            document.getElementById('jadwal_tanggal').value = data.tanggal;
            document.getElementById('jadwal_waktu_mulai').value = data.waktu_mulai ? data.waktu_mulai.substring(0,5) : '';
            document.getElementById('jadwal_waktu_selesai').value = data.waktu_selesai ? data.waktu_selesai.substring(0,5) : '';
            document.getElementById('jadwal_petugas_id').value = data.petugas_id ?? '';
            document.getElementById('jadwal_keterangan').value = data.keterangan ?? '';
            document.getElementById('jadwalModal').style.display = 'flex';
        } catch (error) {
            alert('Terjadi kesalahan: ' + error.message);
        }
    }

    function closeJadwalModal() {
        document.getElementById('jadwalModal').style.display = 'none';
    }

    document.getElementById('jadwalModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeJadwalModal();
        }
    });

// ----- Modal Jadwal Setoran -----
    function openJadwalSetoranModal(setoranId) {
               document.getElementById('jadwalSetoranForm').action = `/admin/setoran/${setoranId}/jadwalkan`;
                document.getElementById('tanggal_jemput').value = '';
                document.getElementById('waktu_mulai_jemput').value = '';
                document.getElementById('waktu_selesai_jemput').value = '';
                document.getElementById('petugas_id_jemput').value = '';
                document.getElementById('keterangan_jemput').value = '';
                document.getElementById('jadwalSetoranModal').style.display = 'flex';
        }

        function closeJadwalSetoranModal() {
            document.getElementById('jadwalSetoranModal').style.display = 'none';
        }

        document.getElementById('jadwalSetoranModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeJadwalSetoranModal();
            }
        });

    // ----- Modal Reward -----
    function openRewardModal() {
        document.getElementById('rewardModalTitle').innerText = 'Tambah Item Reward';
        document.getElementById('rewardForm').action = "{{ route('admin.reward.store') }}";
        document.getElementById('rewardMethodField').innerHTML = '';
        document.getElementById('reward_nama_item').value = '';
        document.getElementById('reward_deskripsi').value = '';
        document.getElementById('reward_poin_diperlukan').value = '';
        document.getElementById('reward_stok').value = '';
        document.getElementById('rewardModal').style.display = 'flex';
    }

    async function editReward(id) {
        try {
            const response = await fetch(`/admin/reward/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Gagal mengambil data');
            const data = await response.json();

            document.getElementById('rewardModalTitle').innerText = 'Edit Item Reward';
            document.getElementById('rewardForm').action = `/admin/reward/${data.id}`;
            document.getElementById('rewardMethodField').innerHTML = `@csrf @method('PUT')`;
            document.getElementById('reward_nama_item').value = data.nama_item;
            document.getElementById('reward_deskripsi').value = data.deskripsi || '';
            document.getElementById('reward_poin_diperlukan').value = data.poin_diperlukan;
            document.getElementById('reward_stok').value = data.stok;
            document.getElementById('rewardModal').style.display = 'flex';
        } catch (error) {
            alert('Terjadi kesalahan: ' + error.message);
        }
    }

    function closeRewardModal() {
        document.getElementById('rewardModal').style.display = 'none';
    }

    document.getElementById('rewardModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRewardModal();
        }
    });
// ----- Modal Artikel -----
    function openArtikelModal() {
            document.getElementById('artikelModalTitle').innerText = 'Tulis Artikel';
            document.getElementById('artikelForm').action = "{{ route('admin.artikel.store') }}";
            document.getElementById('artikelMethodField').innerHTML = '';
            document.getElementById('artikel_judul').value = '';
            document.getElementById('artikel_isi').value = 'data.isi';
            document.getElementById('artikel_tanggal').value = '';
            document.getElementById('artikel_gambar').value = '';
            document.getElementById('artikelModal').style.display = 'flex';
        }

        async function editArtikel(id) {
            try {
                const response = await fetch(`/admin/artikel/${id}/edit`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Gagal mengambil data');
                const data = await response.json();

                document.getElementById('artikelModalTitle').innerText = 'Edit Artikel';
                document.getElementById('artikelForm').action = `/admin/artikel/${data.id}`;
                document.getElementById('artikelMethodField').innerHTML = `@csrf @method('PUT')`;
                document.getElementById('artikel_judul').value = data.judul;
                document.getElementById('artikel_isi').value = data.isi;
                document.getElementById('artikel_tanggal').value = data.tanggal_terbit; // sudah diformat Y-m-d dari controller
                document.getElementById('artikel_gambar').value = ''; // reset input file
                document.getElementById('artikelModal').style.display = 'flex';
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        function closeArtikelModal() {
            document.getElementById('artikelModal').style.display = 'none';
        }

        document.getElementById('artikelModal').addEventListener('click', function(e) {
            if (e.target === this) closeArtikelModal();
        });
// skrip garfik js
// Data dari controller (diambil dari variabel PHP)
const chartLabels = @json($chartLabels);
const chartValues = @json($chartValues);
const pieLabels = @json($pieLabels);
const pieValues = @json($pieValues);

// Bar Chart
const ctxBar = document.getElementById('barChart')?.getContext('2d');
if (ctxBar) {
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Berat (Kg)',
                data: chartValues,
                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// Pie Chart
const ctxPie = document.getElementById('pieChart')?.getContext('2d');
if (ctxPie) {
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#8B5CF6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}
</script>
@endpush