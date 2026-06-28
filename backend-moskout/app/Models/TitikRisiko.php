<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TitikRisiko extends Model
{
    protected $table = 'titik_risikos';

    protected $fillable = [
        'nama_titik', 'alamat', 'rt_rw', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan',
        'latitude', 'longitude',
        'jenis_risiko', 'level_risiko_awal', 'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(PemeriksaanRisiko::class, 'titik_risiko_id');
    }

    public function pemeriksaanTerakhir(): HasOne
    {
        return $this->hasOne(PemeriksaanRisiko::class, 'titik_risiko_id')
                    ->latestOfMany('tanggal_pemeriksaan');
    }
}
