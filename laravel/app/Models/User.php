<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'no_wa',
        'no_kamar',
        'nama_kos',
        'alamat_kos',
    ];

    protected $hidden = [
    'password',
    'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'role' => 'string',
    ];

// Cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    public function isPetugas(): bool
    {
    return $this->role === 'petugas';
    }

// Relasi penukaran poin (sebagai penghuni)
    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class);
    }

// Relasi ke setoran_sampah
    public function setoranSampah()
    {
        return $this->hasMany(SetoranSampah::class);
    }

    // Hitung sisa saldo poin saat ini (Poin Selesai - Poin Dipakai/Pending)
    public function getSaldoPoinAttribute()
    {
        if (!$this->isResident()) {
            return 0;
        }
        $totalPoinDiterima = $this->setoranSampah()->where('status', 'selesai')->sum('poin_didapat');
        $totalPoinDipakai = $this->penukaranPoin()->whereIn('status', ['pending', 'disetujui'])->sum('total_poin');
        return max(0, $totalPoinDiterima - $totalPoinDipakai);
    }

}