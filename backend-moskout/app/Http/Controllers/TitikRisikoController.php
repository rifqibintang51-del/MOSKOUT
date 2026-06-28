<?php

namespace App\Http\Controllers;

use App\Models\TitikRisiko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TitikRisikoController extends Controller
{
    public function index()
    {
        $titikRisikos = TitikRisiko::orderBy('nama_titik')->get();
        return view('admin.titik-risiko.index', compact('titikRisikos'));
    }

    public function create()
    {
        return view('admin.titik-risiko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_titik' => 'required|string|max:150',
            'alamat' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'provinsi' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'jenis_risiko' => 'required|in:genangan,barang bekas,saluran air,tempat sampah',
            'level_risiko_awal' => 'required|in:rendah,sedang,tinggi',
            'status_aktif' => 'required|boolean',
        ]);

        TitikRisiko::create($request->all());

        return redirect()->route('admin.titik-risiko.index')
            ->with('success', 'Titik Risiko berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $titikRisiko = TitikRisiko::findOrFail($id);
        return view('admin.titik-risiko.edit', compact('titikRisiko'));
    }

    public function update(Request $request, $id)
    {
        $titikRisiko = TitikRisiko::findOrFail($id);

        $request->validate([
            'nama_titik' => 'required|string|max:150',
            'alamat' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'provinsi' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'jenis_risiko' => 'required|in:genangan,barang bekas,saluran air,tempat sampah',
            'level_risiko_awal' => 'required|in:rendah,sedang,tinggi',
            'status_aktif' => 'required|boolean',
        ]);

        $titikRisiko->update($request->all());

        return redirect()->route('admin.titik-risiko.index')
            ->with('success', 'Titik Risiko berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $titikRisiko = TitikRisiko::findOrFail($id);
        $titikRisiko->delete();

        return redirect()->route('admin.titik-risiko.index')
            ->with('success', 'Titik Risiko berhasil dihapus.');
    }

    public function wilayah($type, $id = null)
    {
        try {
            $base = 'https://www.emsifa.com/api-wilayah-indonesia/api';
            $url = $base . '/' . $type . ($id ? '/' . $id : '') . '.json';
            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                return response($response->body())->header('Content-Type', 'application/json');
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            report($e);
        }

        return response('[]', 200)->header('Content-Type', 'application/json');
    }
}
