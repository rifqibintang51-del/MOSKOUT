@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat 1: Total Pemeriksaan -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <span class="text-muted text-uppercase fw-bold small">Total Pemeriksaan</span>
                <h2 class="mt-2 mb-0 fw-bold">{{ $totalPemeriksaan }}</h2>
                <p class="text-muted small mb-0 mt-1"><i class="bi bi-clipboard2-check"></i> Seluruh laporan</p>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: rgba(13, 148, 136, 0.1); color: var(--primary-color);">
                <i class="bi bi-clipboard2-check" style="font-size: 1.75rem;"></i>
            </div>
        </div>
    </div>

    <!-- Stat 2: Pemeriksaan Bulan Ini -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <span class="text-muted text-uppercase fw-bold small">Bulan Ini</span>
                <h2 class="mt-2 mb-0 fw-bold" style="color: var(--primary-color);">{{ $pemeriksaanBulanIni }}</h2>
                <p class="small mb-0 mt-1" style="color: var(--primary-color);"><i class="bi bi-calendar3"></i> Pemeriksaan bulan ini</p>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-calendar-check" style="font-size: 1.75rem;"></i>
            </div>
        </div>
    </div>

    <!-- Stat 3: Jentik Ditemukan -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <span class="text-muted text-uppercase fw-bold small">Jentik Ditemukan</span>
                <h2 class="mt-2 mb-0 fw-bold text-danger">{{ $totalJentikDitemukan }}</h2>
                <p class="text-danger-emphasis small mb-0 mt-1"><i class="bi bi-exclamation-triangle-fill"></i> Butuh perhatian</p>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: rgba(244, 63, 94, 0.1); color: var(--secondary-color);">
                <i class="bi bi-bug" style="font-size: 1.75rem;"></i>
            </div>
        </div>
    </div>

    <!-- Stat 4: Titik Aktif -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <span class="text-muted text-uppercase fw-bold small">Titik Aktif</span>
                <h2 class="mt-2 mb-0 fw-bold" style="color: #f59e0b;">{{ $totalTitikAktif }}</h2>
                <p class="small mb-0 mt-1" style="color: #d97706;"><i class="bi bi-geo-alt-fill"></i> Lokasi dipantau</p>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="bi bi-geo-alt" style="font-size: 1.75rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pemeriksaan Terbaru -->
    <div class="col-12">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color: var(--primary-color);"></i>Pemeriksaan Terbaru</h5>
                <a href="{{ route('petugas.pemeriksaan-risiko.index') }}" class="btn btn-light btn-sm fw-bold">Semua Data</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Titik Risiko</th>
                            <th>Tanggal</th>
                            <th class="text-center">Jentik</th>
                            <th class="text-center">Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemeriksaanTerbaru as $pem)
                            <tr>
                                <td>
                                    @if($pem->titikRisiko)
                                        <div class="fw-bold">{{ $pem->titikRisiko->nama_titik }}</div>
                                        <small class="text-muted">{{ Str::limit($pem->titikRisiko->alamat, 35) }}</small>
                                    @else
                                        <span class="text-danger small">Titik Dihapus</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($pem->tanggal_pemeriksaan)->format('d M Y') }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $pem->ditemukan_jentik == 'ya' ? 'bg-danger' : 'bg-success' }} px-2 py-1">
                                        {{ strtoupper($pem->ditemukan_jentik) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = [
                                            'aman' => 'bg-success',
                                            'perlu pemantauan' => 'bg-warning text-dark',
                                            'perlu tindakan' => 'bg-danger'
                                        ][$pem->status_akhir] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge badge-pill {{ $statusClass }}">{{ ucwords($pem->status_akhir) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat pemeriksaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
