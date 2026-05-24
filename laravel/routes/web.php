<?php

use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// ======================= ROUTE RESIDENT =======================
Route::middleware(['auth', 'resident'])
    ->prefix('resident')
    ->name('resident.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Resident\DashboardController::class, 'index'])
            ->name('dashboard');

        // Setoran sampah: create, store, index, show
        Route::resource('setoran', App\Http\Controllers\Resident\SetoranController::class)
            ->only(['index', 'create', 'store', 'show']);

        // Konfirmasi selesai jika sudah diangkut (nanti diimplementasikan)
        Route::put('/setoran/{setoran}/selesai', [App\Http\Controllers\Resident\SetoranController::class, 'selesai'])
            ->name('setoran.selesai');

        // Jadwal pengangkutan (hanya lihat)
        Route::get('/jadwal', [App\Http\Controllers\Resident\JadwalController::class, 'index'])
            ->name('jadwal.index');

        // Artikel edukasi
        Route::get('/artikel', [App\Http\Controllers\Resident\ArtikelController::class, 'index'])
            ->name('artikel.index');
        Route::get('/artikel/{artikel}', [App\Http\Controllers\Resident\ArtikelController::class, 'show'])
            ->name('artikel.show');

        // Profil
        Route::get('/profile', [App\Http\Controllers\Resident\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Resident\ProfileController::class, 'update'])
            ->name('profile.update');

        // Reward: lihat katalog dan riwayat penukaran
        Route::get('/reward', [App\Http\Controllers\Resident\RewardController::class, 'index'])
            ->name('reward.index');
        Route::post('/reward/tukar', [App\Http\Controllers\Resident\RewardController::class, 'tukar'])
            ->name('reward.tukar');
        Route::get('/reward/riwayat', [App\Http\Controllers\Resident\RewardController::class, 'riwayat'])
            ->name('reward.riwayat');
    });

// ======================= ROUTE PETUGAS =======================
Route::middleware(['auth', 'petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Petugas\DashboardController::class, 'index'])
            ->name('dashboard');

        // Lihat daftar tugas penjemputan
        Route::get('/tugas', [App\Http\Controllers\Petugas\TugasController::class, 'index'])
            ->name('tugas.index');

        // Konfirmasi pengangkutan (ubah status setoran jadi 'diangkut')
        Route::put('/tugas/{setoran}/konfirmasi', [App\Http\Controllers\Petugas\TugasController::class, 'konfirmasi'])
            ->name('tugas.konfirmasi');
    });

// ======================= ROUTE ADMIN =======================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Manajemen pengguna (CRUD)
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);

        // Kategori sampah
        Route::resource('kategori', App\Http\Controllers\Admin\KategoriController::class);

        // Setoran sampah (lihat semua, verifikasi, update)
        Route::resource('setoran', App\Http\Controllers\Admin\SetoranController::class)
            ->only(['index', 'show', 'edit', 'update']);

        // Verifikasi berat aktual & selesaikan
        Route::put('/setoran/{setoran}/verifikasi', [App\Http\Controllers\Admin\SetoranController::class, 'verifikasi'])
            ->name('setoran.verifikasi');

        // Jadwal pengangkutan (CRUD)
        Route::resource('jadwal', App\Http\Controllers\Admin\JadwalController::class);

        // Artikel edukasi (CRUD)
        Route::resource('artikel', App\Http\Controllers\Admin\ArtikelController::class);

        // Reward: kelola katalog dan konfirmasi penukaran
        Route::resource('reward', App\Http\Controllers\Admin\RewardController::class);
        Route::put('/reward/penukaran/{penukaran}/proses', [App\Http\Controllers\Admin\RewardController::class, 'prosesPenukaran'])
            ->name('reward.proses-penukaran');
    });