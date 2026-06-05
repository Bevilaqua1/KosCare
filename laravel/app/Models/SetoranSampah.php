<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranSampah extends Model
{
    use HasFactory;

    protected $table = 'setoran_sampah';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'estimasi_berat',   // ✅ sesuaikan dengan nama kolom di database
        'tanggal_setor',
        'foto',
        'status',
        'berat_aktual',
        'poin_didapat',
        'petugas_id',
        'jadwal_id'         // ✅ kolom baru
    ];

    protected $casts = [
        'tanggal_setor' => 'date',
        'status' => 'string',
    ];

    // Relasi ke User (penghuni)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke KategoriSampah
    public function kategori()
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_id');
    }

    // Relasi ke User (petugas)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Relasi ke JadwalPengangkutan
    public function jadwal()
    {
        return $this->belongsTo(JadwalPengangkutan::class, 'jadwal_id');
    }
}