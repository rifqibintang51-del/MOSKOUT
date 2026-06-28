<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TitikRisiko;
use App\Models\PemeriksaanRisiko;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $query = PemeriksaanRisiko::with(['titikRisiko', 'petugas']);

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->tanggal_selesai);
        }

        // Filter by status akhir
        if ($request->filled('status_akhir')) {
            $query->where('status_akhir', $request->status_akhir);
        }

        // Filter by petugas
        if ($request->filled('petugas_id')) {
            $query->where('petugas_id', $request->petugas_id);
        }

        // Filter by level risiko (via titik risiko)
        if ($request->filled('level_risiko')) {
            $titikIds = TitikRisiko::where('level_risiko_awal', $request->level_risiko)->pluck('id');
            $query->whereIn('titik_risiko_id', $titikIds);
        }

        $pemeriksaans = $query->orderBy('tanggal_pemeriksaan', 'desc')->get();

        // Summary stats
        $totalPemeriksaan = $pemeriksaans->count();
        $totalJentikYa = $pemeriksaans->where('ditemukan_jentik', 'ya')->count();
        $totalPerluTindakan = $pemeriksaans->where('status_akhir', 'perlu tindakan')->count();
        $totalAman = $pemeriksaans->where('status_akhir', 'aman')->count();
        $totalPerluPemantauan = $pemeriksaans->where('status_akhir', 'perlu pemantauan')->count();

        // Per titik risiko
        $rekapTitik = TitikRisiko::withCount('pemeriksaans')
            ->with('pemeriksaanTerakhir')
            ->orderBy('pemeriksaans_count', 'desc')
            ->get();

        // Data for chart (per bulan)
        $chartBulan = $pemeriksaans->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->format('Y-m'))
            ->sortKeys()
            ->map(fn($items) => $items->count());

        // Data for pie chart (status akhir)
        $chartStatus = [
            'Aman' => $totalAman,
            'Perlu Pemantauan' => $totalPerluPemantauan,
            'Perlu Tindakan' => $totalPerluTindakan,
        ];

        // Filter dropdown data
        $petugasList = User::where('role', 'petugas')->orderBy('name')->get();

        return view('admin.rekap.index', compact(
            'pemeriksaans',
            'totalPemeriksaan',
            'totalJentikYa',
            'totalPerluTindakan',
            'totalAman',
            'totalPerluPemantauan',
            'rekapTitik',
            'chartBulan',
            'chartStatus',
            'petugasList'
        ));
    }
}
