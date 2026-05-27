<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPengangkutan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pengangkutan';

    protected $fillable = [
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'keterangan',
        'petugas_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Relasi ke petugas
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}