<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanRisiko;
use App\Models\TitikRisiko;
use Carbon\Carbon;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        $totalPemeriksaan = PemeriksaanRisiko::count();
        $pemeriksaanBulanIni = PemeriksaanRisiko::whereMonth('tanggal_pemeriksaan', Carbon::now()->month)
            ->whereYear('tanggal_pemeriksaan', Carbon::now()->year)
            ->count();
        $totalJentikDitemukan = PemeriksaanRisiko::where('ditemukan_jentik', 'ya')->count();
        $totalTitikAktif = TitikRisiko::where('status_aktif', true)->count();

        $pemeriksaanTerbaru = PemeriksaanRisiko::with('titikRisiko')
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'totalPemeriksaan',
            'pemeriksaanBulanIni',
            'totalJentikDitemukan',
            'totalTitikAktif',
            'pemeriksaanTerbaru'
        ));
    }
}
