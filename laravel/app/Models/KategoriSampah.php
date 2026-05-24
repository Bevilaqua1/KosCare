<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSampah extends Model
{
    use HasFactory;

    protected $table = 'kategori_sampah';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    // Relasi: satu kategori memiliki banyak setoran sampah
    public function setoranSampah()
    {
        return $this->hasMany(SetoranSampah::class, 'kategori_id');
    }
}