<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenukaranPoin extends Model
{

    protected $table = 'penukaran_poin';

    protected $fillable = ['user_id', 'kategori_reward_id', 'jumlah', 'total_poin', 'status', 'tanggal_penukaran'];

    protected $casts = [
    'tanggal_penukaran' => 'date',
    'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriReward()
    {
        return $this->belongsTo(KategoriReward::class);
    }
}
