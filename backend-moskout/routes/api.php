<?php

use App\Http\Controllers\ApiWaspadaController;
use Illuminate\Support\Facades\Route;

Route::get('/titik-risiko', [ApiWaspadaController::class, 'index']);
Route::get('/titik-risiko/level/{level}', [ApiWaspadaController::class, 'filterByLevel']);
Route::get('/titik-risiko/{id}/pemeriksaan', [ApiWaspadaController::class, 'pemeriksaanByTitik']);
Route::post('/pemeriksaan-risiko', [ApiWaspadaController::class, 'store']);
