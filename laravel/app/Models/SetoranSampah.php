<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranSampah extends Model
{
    use HasFactory;

    protected $table = 'setoran_sampah';
    protected $fillable = [
        'user_id', 'kategori_id', 'estimasi_berat', 'berat_aktual',
        'tanggal_setor', 'foto', 'status'
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
}