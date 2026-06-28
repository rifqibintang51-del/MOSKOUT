<?php

namespace App\Http\Controllers;

use App\Models\TitikRisiko;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTitik = TitikRisiko::count();
        $totalTinggi = TitikRisiko::where('level_risiko_awal', 'tinggi')->count();

        $titikTerbaru = TitikRisiko::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTitik',
            'totalTinggi',
            'titikTerbaru'
        ));
    }
}
