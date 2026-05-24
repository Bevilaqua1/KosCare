<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtikelEdukasi extends Model
{
    use HasFactory;

    protected $table = 'artikel_edukasi';
    protected $fillable = ['judul', 'isi', 'gambar', 'tanggal_terbit'];
}