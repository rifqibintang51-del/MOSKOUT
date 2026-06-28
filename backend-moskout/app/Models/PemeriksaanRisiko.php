<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemeriksaanRisiko extends Model
{
    protected $table = 'pemeriksaan_risikos';

    protected $fillable = [
        'titik_risiko_id', 'petugas_id', 'tanggal_pemeriksaan',
        'ditemukan_jentik', 'kondisi_lingkungan', 'tindakan_dilakukan',
        'status_akhir', 'revisi_ke',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pemeriksaan' => 'date:Y-m-d',
        ];
    }

    public function titikRisiko(): BelongsTo
    {
        return $this->belongsTo(TitikRisiko::class, 'titik_risiko_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
