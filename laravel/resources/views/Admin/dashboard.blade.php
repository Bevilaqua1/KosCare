@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    {{-- Pesan sukses --}}
    @if(session('success'))
    <div style="background: var(--success-bg); color: var(--primary-dark); padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
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
                        @forelse($setoransValidasi->take(1) as $setoran)
                        <tr>
                            <td><strong>#{{ $setoran->id }}</strong><br><span class="text-sm">{{ optional($setoran->user)->no_kamar ?? '-' }}</span></td>
                            <td>{{ $setoran->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $setoran->tanggal_setor->format('d M Y') ?? '-' }}</td>
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
                        @forelse($setoransValidasi as $setoran)
                        <tr>
                            <td>
                                <strong>#{{ $setoran->id }}</strong><br>
                                <span class="text-sm">{{ optional($setoran->user)->no_kamar ?? '-' }} ({{ optional($setoran->user)->name ?? 'Tidak Dikenal' }})</span>
                            </td>
                            <td><span class="badge badge-info">{{ $setoran->kategori->nama_kategori ?? '-' }}</span></td>
                            <td>
                                @if($setoran->foto)
                                    <img src="{{ asset('storage/' . $setoran->foto) }}" alt="Foto" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                                @else
                                    <div style="width:48px;height:48px;background:var(--border);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.setoran.verifikasi', $setoran->id) }}" method="POST" class="form-verifikasi">
                                    @csrf
                                    @method('PUT')
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <input type="number" name="berat_aktual" class="form-control" 
                                               style="width:80px; padding:8px;" step="0.1" required>
                                        <span>Kg</span>
                                    </div>
                                </form>
                            </td>
                            <td class="action-buttons">
                                <button type="button" class="btn btn-small" style="background: var(--primary);"
                                        onclick="event.preventDefault(); this.closest('tr').querySelector('.form-verifikasi').submit();">
                                    <i class="fa-solid fa-check"></i> Setujui
                                </button>
                                <form action="{{ route('admin.setoran.tolak', $setoran->id) }}" method="POST" style="display:inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-small btn-danger btn-icon" title="Tolak">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada setoran yang perlu divalidasi.</td>
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
                <h3 class="panel-title">Manajemen Jadwal</h3>
                <button class="btn btn-small" onclick="openJadwalModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Jadwal
                </button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                        <tr>
                            <td>{{ optional($jadwal->tanggal)->format('d M Y') }}</td>
                            <td>{{ optional($jadwal->waktu_mulai)->format('H:i') }} - {{ optional($jadwal->waktu_selesai)->format('H:i') }}</td>
                            <td>{{ optional($jadwal->petugas)->name ?? 'Belum ditugaskan' }}</td>
                            <td>{{ $jadwal->keterangan ?? '-' }}</td>
                            <td class="action-buttons">
                                <button class="btn btn-small btn-outline btn-icon" onclick="editJadwal({{ $jadwal->id }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-small btn-danger btn-icon" onclick="return confirm('Yakin hapus jadwal?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Belum ada jadwal pengangkutan.</td>
                        </tr>
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
            document.getElementById('methodField').innerHTML = '@csrf @method('PUT')';
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
            document.getElementById('jadwalMethodField').innerHTML = '@csrf @method('PUT')';
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
</script>
@endpush