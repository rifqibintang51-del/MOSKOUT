<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanRisiko;
use App\Models\TitikRisiko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanRisikoController extends Controller
{
    public function index()
    {
        $pemeriksaans = PemeriksaanRisiko::with('titikRisiko')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('petugas.pemeriksaan-risiko.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $titikRisikos = TitikRisiko::where('status_aktif', true)->orderBy('nama_titik')->get();
        return view('petugas.pemeriksaan-risiko.create', compact('titikRisikos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titik_risiko_id' => 'required|exists:titik_risikos,id',
            'petugas_id' => 'required|integer|min:1',
            'tanggal_pemeriksaan' => 'required|date|before_or_equal:today',
            'ditemukan_jentik' => 'required|in:ya,tidak',
            'kondisi_lingkungan' => 'required|string|min:10',
            'tindakan_dilakukan' => 'required|string|min:5',
            'status_akhir' => 'required|in:aman,perlu pemantauan,perlu tindakan',
        ]);

        $data = $request->all();
        $data['revisi_ke'] = 1;
        PemeriksaanRisiko::create($data);

        return redirect()->route('petugas.pemeriksaan-risiko.index')
            ->with('success', 'Pemeriksaan Risiko berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemeriksaan = PemeriksaanRisiko::findOrFail($id);
        $titikRisikos = TitikRisiko::where('status_aktif', true)->orderBy('nama_titik')->get();
        return view('petugas.pemeriksaan-risiko.edit', compact('pemeriksaan', 'titikRisikos'));
    }

    public function update(Request $request, $id)
    {
        $pemeriksaan = PemeriksaanRisiko::findOrFail($id);

        $request->validate([
            'titik_risiko_id' => 'required|exists:titik_risikos,id',
            'petugas_id' => 'required|integer|min:1',
            'tanggal_pemeriksaan' => 'required|date|before_or_equal:today',
            'ditemukan_jentik' => 'required|in:ya,tidak',
            'kondisi_lingkungan' => 'required|string|min:10',
            'tindakan_dilakukan' => 'required|string|min:5',
            'status_akhir' => 'required|in:aman,perlu pemantauan,perlu tindakan',
        ]);

        $revisiKe = PemeriksaanRisiko::where('titik_risiko_id', $request->titik_risiko_id)
            ->where('tanggal_pemeriksaan', $request->tanggal_pemeriksaan)
            ->max('revisi_ke') ?? 0;

        $data = $request->all();
        $data['revisi_ke'] = $revisiKe + 1;
        PemeriksaanRisiko::create($data);

        return redirect()->route('petugas.pemeriksaan-risiko.index')
            ->with('success', "Pemeriksaan Risiko berhasil diperbarui (Revisi #{$data['revisi_ke']}). Riwayat sebelumnya tetap tersimpan.");
    }

    public function destroy($id)
    {
        $pemeriksaan = PemeriksaanRisiko::findOrFail($id);
        $pemeriksaan->delete();

        return redirect()->route('petugas.pemeriksaan-risiko.index')
            ->with('success', 'Pemeriksaan Risiko berhasil dihapus.');
    }
}
