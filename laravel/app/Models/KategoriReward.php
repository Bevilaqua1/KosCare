<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriReward extends Model
{
    protected $table = 'kategori_reward';

    protected $fillable = ['nama_item', 'deskripsi', 'poin_diperlukan', 'stok', 'gambar'];

    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class);
    }
}
