<?php

namespace App\Http\Controllers;

use App\Models\TitikRisiko;
use App\Models\PemeriksaanRisiko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiWaspadaController extends Controller
{
    /**
     * GET /api/titik-risiko
     * Mengembalikan semua titik risiko beserta riwayat pemeriksaan terakhirnya.
     */
    public function index()
    {
        $titikRisikos = TitikRisiko::with('pemeriksaanTerakhir')
            ->where('status_aktif', true)
            ->orderBy('nama_titik')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar titik risiko berhasil dimuat',
            'data' => $titikRisikos,
        ]);
    }

    /**
     * GET /api/titik-risiko/{id}/pemeriksaan
     * Mengembalikan seluruh riwayat pemeriksaan dari satu titik risiko spesifik.
     */
    public function pemeriksaanByTitik($id)
    {
        $titikRisiko = TitikRisiko::find($id);

        if (!$titikRisiko) {
            return response()->json([
                'success' => false,
                'message' => 'Titik risiko tidak ditemukan',
            ], 404);
        }

        $pemeriksaans = PemeriksaanRisiko::where('titik_risiko_id', $id)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pemeriksaan berhasil dimuat',
            'titik_risiko' => $titikRisiko,
            'data' => $pemeriksaans,
        ]);
    }

    /**
     * POST /api/pemeriksaan-risiko
     * Menginput data pemeriksaan baru dengan validasi ketat.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titik_risiko_id'       => 'required|exists:titik_risikos,id',
            'petugas_id'            => 'required|integer|exists:users,id',
            'tanggal_pemeriksaan'   => 'required|date|before_or_equal:today',
            'ditemukan_jentik'      => 'required|in:ya,tidak',
            'kondisi_lingkungan'    => 'required|string|min:10',
            'tindakan_dilakukan'    => 'required|string|min:5',
            'status_akhir'          => 'required|in:aman,perlu pemantauan,perlu tindakan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['revisi_ke'] = 1;
        $pemeriksaan = PemeriksaanRisiko::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data pemeriksaan berhasil disimpan',
            'data' => $pemeriksaan,
        ], 201);
    }

    /**
     * GET /api/titik-risiko/level/{level}
     * Memfilter data titik risiko berdasarkan level risiko.
     */
    public function filterByLevel($level)
    {
        $validLevels = ['rendah', 'sedang', 'tinggi'];

        if (!in_array($level, $validLevels)) {
            return response()->json([
                'success' => false,
                'message' => 'Level risiko tidak valid. Gunakan: rendah, sedang, atau tinggi.',
            ], 400);
        }

        $titikRisikos = TitikRisiko::with('pemeriksaanTerakhir')
            ->where('level_risiko_awal', $level)
            ->where('status_aktif', true)
            ->orderBy('nama_titik')
            ->get();

        return response()->json([
            'success' => true,
            'message' => "Data titik risiko level {$level} berhasil dimuat",
            'data' => $titikRisikos,
        ]);
    }
}
