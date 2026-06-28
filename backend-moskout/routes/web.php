<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TitikRisikoController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\PemeriksaanRisikoController;
use App\Http\Controllers\RekapController;

// Redirect welcome page to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Admin Protected Routes (role: admin) ──────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rekap & Laporan
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');

    // CRUD Titik Risiko
    Route::get('/titik-risiko', [TitikRisikoController::class, 'index'])->name('titik-risiko.index');
    Route::get('/titik-risiko/create', [TitikRisikoController::class, 'create'])->name('titik-risiko.create');
    Route::post('/titik-risiko', [TitikRisikoController::class, 'store'])->name('titik-risiko.store');
    Route::get('/titik-risiko/{id}/edit', [TitikRisikoController::class, 'edit'])->name('titik-risiko.edit');
    Route::put('/titik-risiko/{id}', [TitikRisikoController::class, 'update'])->name('titik-risiko.update');
    Route::delete('/titik-risiko/{id}', [TitikRisikoController::class, 'destroy'])->name('titik-risiko.destroy');

    // Proxy wilayah (cascading dropdown)
    Route::get('/wilayah/{type}/{id?}', [TitikRisikoController::class, 'wilayah'])
        ->whereIn('type', ['provinces', 'regencies', 'districts', 'villages'])
        ->whereNumber('id')
        ->name('wilayah');
});

// ─── Petugas Protected Routes (role: petugas) ───────────────────────
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

    // CRUD Pemeriksaan Risiko
    Route::get('/pemeriksaan-risiko', [PemeriksaanRisikoController::class, 'index'])->name('pemeriksaan-risiko.index');
    Route::get('/pemeriksaan-risiko/create', [PemeriksaanRisikoController::class, 'create'])->name('pemeriksaan-risiko.create');
    Route::post('/pemeriksaan-risiko', [PemeriksaanRisikoController::class, 'store'])->name('pemeriksaan-risiko.store');
    Route::get('/pemeriksaan-risiko/{id}/edit', [PemeriksaanRisikoController::class, 'edit'])->name('pemeriksaan-risiko.edit');
    Route::put('/pemeriksaan-risiko/{id}', [PemeriksaanRisikoController::class, 'update'])->name('pemeriksaan-risiko.update');
    Route::delete('/pemeriksaan-risiko/{id}', [PemeriksaanRisikoController::class, 'destroy'])->name('pemeriksaan-risiko.destroy');
});
