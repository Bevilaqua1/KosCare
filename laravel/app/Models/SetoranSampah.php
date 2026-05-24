<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranSampah extends Model
{
    use HasFactory;

    protected $table = 'setoran_sampah';
    protected $fillable = [
    'user_id', 'kategori_id', 'berat_estimasi', 'tanggal_setor',
    'foto', 'status', 'berat_aktual', 'poin_didapat', 'petugas_id'
    ];


    protected $casts = [
    'tanggal_setor' => 'date',
    'status' => 'string',
    ];

    // Relasi belongsTo ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi belongsTo ke KategoriSampah
    public function kategori()
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_id');
    }

    //relassi ke petugassampah
    public function petugas()
    {
    return $this->belongsTo(User::class, 'petugas_id');
    }

}