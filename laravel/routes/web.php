<?php

use Illuminate\Support\Facades\Route;

// Root: redirect guest ke login, user login dapat 404
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isPetugas()) {
            return redirect()->route('petugas.dashboard');
        } elseif ($user->isResident()) {
            return redirect()->route('resident.dashboard');
        }
        return abort(404);
    }
    return redirect('/login');
});

// Auth routes (Breeze) – sudah termasuk POST logout
require __DIR__.'/auth.php';

// ======================= ROUTE RESIDENT =======================
Route::middleware(['auth', 'resident'])
    ->prefix('resident')
    ->name('resident.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Resident\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('setoran', App\Http\Controllers\Resident\SetoranController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::put('/setoran/{setoran}/selesai', [App\Http\Controllers\Resident\SetoranController::class, 'selesai'])
            ->name('setoran.selesai');

        Route::get('/jadwal', [App\Http\Controllers\Resident\JadwalController::class, 'index'])
            ->name('jadwal.index');

        Route::get('/artikel', [App\Http\Controllers\Resident\ArtikelController::class, 'index'])
            ->name('artikel.index');
        Route::get('/artikel/{artikel}', [App\Http\Controllers\Resident\ArtikelController::class, 'show'])
            ->name('artikel.show');

        Route::get('/profile', [App\Http\Controllers\Resident\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Resident\ProfileController::class, 'update'])
            ->name('profile.update');

        Route::get('/reward', [App\Http\Controllers\Resident\RewardController::class, 'index'])
            ->name('reward.index');
        Route::post('/reward/tukar', [App\Http\Controllers\Resident\RewardController::class, 'store'])
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

        Route::get('/tugas', [App\Http\Controllers\Petugas\TugasController::class, 'index'])
            ->name('tugas.index');

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

        Route::resource('users', App\Http\Controllers\Admin\UserController::class);

        Route::resource('kategori', App\Http\Controllers\Admin\KategoriController::class);

        Route::resource('setoran', App\Http\Controllers\Admin\SetoranController::class)
            ->only(['index', 'show', 'edit', 'update']);

        Route::put('/setoran/{setoran}/verifikasi', [App\Http\Controllers\Admin\SetoranController::class, 'verifikasi'])
            ->name('setoran.verifikasi');

        Route::put('/setoran/{setoran}/tolak', [App\Http\Controllers\Admin\SetoranController::class, 'tolak'])
            ->name('setoran.tolak');
           
        Route::get('/setoran/{setoran}/jadwalkan', [App\Http\Controllers\Admin\SetoranController::class, 'jadwalkanForm'])
            ->name('setoran.jadwalkan');
        Route::post('/setoran/{setoran}/jadwalkan', [App\Http\Controllers\Admin\SetoranController::class, 'jadwalkan'])
            ->name('setoran.jadwalkan.submit');
            
        Route::resource('jadwal', App\Http\Controllers\Admin\JadwalController::class);

        Route::resource('artikel', App\Http\Controllers\Admin\ArtikelController::class);

        Route::resource('reward', App\Http\Controllers\Admin\RewardController::class);
        Route::put('/reward/penukaran/{penukaran}/proses', [App\Http\Controllers\Admin\RewardController::class, 'prosesPenukaran'])
            ->name('reward.proses-penukaran');
    });
