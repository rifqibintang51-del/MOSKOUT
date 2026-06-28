@extends('layouts.admin')

@section('title', 'Rekap & Laporan')

@section('styles')
<style>
    .filter-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    .section-title {
        font-weight: 700;
        letter-spacing: -0.3px;
        margin-bottom: 1.25rem;
    }
    .rekap-tabel th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: var(--text-light);
        border-bottom: 2px solid var(--border-color);
        padding: 1rem;
    }
    .rekap-tabel td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-bar-chart-fill text-primary"></i> Rekap & Laporan</h2>
        <p class="text-muted">Rekapitulasi dan analisis data pemeriksaan risiko DBD secara menyeluruh.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filter Form -->
<div class="filter-card">
    <form method="GET" action="{{ route('admin.rekap') }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-3">
            <label class="form-label fw-semibold small text-muted">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label fw-semibold small text-muted">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
        </div>
        <div class="col-12 col-md-2">
            <label class="form-label fw-semibold small text-muted">Status Akhir</label>
            <select name="status_akhir" class="form-select">
                <option value="">Semua</option>
                <option value="aman" {{ request('status_akhir') == 'aman' ? 'selected' : '' }}>Aman</option>
                <option value="perlu pemantauan" {{ request('status_akhir') == 'perlu pemantauan' ? 'selected' : '' }}>Perlu Pemantauan</option>
                <option value="perlu tindakan" {{ request('status_akhir') == 'perlu tindakan' ? 'selected' : '' }}>Perlu Tindakan</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <label class="form-label fw-semibold small text-muted">Petugas</label>
            <select name="petugas_id" class="form-select">
                <option value="">Semua</option>
                @foreach($petugasList as $p)
                    <option value="{{ $p->id }}" {{ request('petugas_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2">
            <label class="form-label fw-semibold small text-muted">Level Risiko</label>
            <select name="level_risiko" class="form-select">
                <option value="">Semua</option>
                <option value="rendah" {{ request('level_risiko') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                <option value="sedang" {{ request('level_risiko') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="tinggi" {{ request('level_risiko') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
            </select>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Terapkan Filter</button>
            <a href="{{ route('admin.rekap') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="glass-card p-4 stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted text-uppercase fw-bold small">Total Pemeriksaan</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                    <i class="bi bi-clipboard-check" style="font-size: 1.3rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0">{{ $totalPemeriksaan }}</h3>
            <p class="text-muted small mb-0 mt-1">Total laporan pemeriksaan</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="glass-card p-4 stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted text-uppercase fw-bold small">Jentik Ditemukan</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: rgba(244, 63, 94, 0.1); color: var(--secondary-color);">
                    <i class="bi bi-bug" style="font-size: 1.3rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-danger">{{ $totalJentikYa }}</h3>
            <p class="text-muted small mb-0 mt-1">Positif ditemukan jentik</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="glass-card p-4 stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted text-uppercase fw-bold small">Perlu Tindakan</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: rgba(255, 193, 7, 0.1); color: #f59e0b;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 1.3rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-warning">{{ $totalPerluTindakan }}</h3>
            <p class="text-muted small mb-0 mt-1">Status perlu tindakan</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="glass-card p-4 stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted text-uppercase fw-bold small">Status Aman</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: rgba(34, 197, 94, 0.1); color: #22c55e;">
                    <i class="bi bi-shield-check" style="font-size: 1.3rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0 text-success">{{ $totalAman }}</h3>
            <p class="text-muted small mb-0 mt-1">Lingkungan aman</p>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-8">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-graph-up me-2 text-primary"></i>Pemeriksaan per Bulan</h5>
            <div class="chart-container">
                <canvas id="chartBulan"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2 text-primary"></i>Distribusi Status</h5>
            <div class="chart-container">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Rekap per Titik Risiko -->
<div class="glass-card p-4 mb-4">
    <h5 class="section-title"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>Rekap per Titik Risiko</h5>
    <div class="table-responsive">
        <table class="table rekap-tabel">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Titik</th>
                    <th class="text-center">Level Awal</th>
                    <th class="text-center">Jumlah Pemeriksaan</th>
                    <th class="text-center">Status Terakhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapTitik as $index => $titik)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $titik->nama_titik }}</td>
                        <td class="text-center">
                            @php
                                $levelClass = ['tinggi' => 'bg-danger', 'sedang' => 'bg-warning text-dark', 'rendah' => 'bg-success'][$titik->level_risiko_awal] ?? 'bg-secondary';
                            @endphp
                            <span class="badge badge-pill {{ $levelClass }}">{{ strtoupper($titik->level_risiko_awal) }}</span>
                        </td>
                        <td class="text-center fw-bold">{{ $titik->pemeriksaans_count }}</td>
                        <td class="text-center">
                            @if($titik->pemeriksaanTerakhir)
                                @php
                                    $cls = ['aman' => 'bg-success', 'perlu pemantauan' => 'bg-warning text-dark', 'perlu tindakan' => 'bg-danger'][$titik->pemeriksaanTerakhir->status_akhir] ?? 'bg-secondary';
                                @endphp
                                <span class="badge badge-pill {{ $cls }}">{{ ucwords($titik->pemeriksaanTerakhir->status_akhir) }}</span>
                            @else
                                <span class="badge bg-secondary">Belum diperiksa</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada titik risiko.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Detail Pemeriksaan -->
<div class="glass-card p-4">
    <h5 class="section-title"><i class="bi bi-list-ul me-2 text-primary"></i>Detail Pemeriksaan</h5>
    <div class="table-responsive">
        <table class="table rekap-tabel">
            <thead>
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Titik Risiko</th>
                    <th>Petugas</th>
                    <th>Tanggal</th>
                    <th class="text-center">Revisi</th>
                    <th class="text-center">Jentik</th>
                    <th>Kondisi & Tindakan</th>
                    <th class="text-center">Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemeriksaans as $index => $pem)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @if($pem->titikRisiko)
                                <div class="fw-bold">{{ $pem->titikRisiko->nama_titik }}</div>
                                <small class="text-muted">{{ Str::limit($pem->titikRisiko->alamat, 40) }}</small>
                            @else
                                <span class="text-danger small">Titik Risiko Dihapus</span>
                            @endif
                        </td>
                        <td>
                            @if($pem->petugas)
                                <span>{{ $pem->petugas->name }}</span>
                            @else
                                <span class="text-muted">Petugas ID: {{ $pem->petugas_id }}</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pem->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if($pem->revisi_ke > 1)
                                <span class="badge bg-info text-dark px-2 py-1" title="Revisi ke-{{ $pem->revisi_ke }}">
                                    <i class="bi bi-arrow-repeat me-1"></i>{{ $pem->revisi_ke }}
                                </span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">Awal</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $pem->ditemukan_jentik == 'ya' ? 'bg-danger' : 'bg-success' }} px-2 py-1">
                                {{ strtoupper($pem->ditemukan_jentik) }}
                            </span>
                        </td>
                        <td>
                            <div class="small"><strong>Lingkungan:</strong> {{ Str::limit($pem->kondisi_lingkungan, 50) }}</div>
                            <div class="small text-muted"><strong>Tindakan:</strong> {{ Str::limit($pem->tindakan_dilakukan, 50) }}</div>
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
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data pemeriksaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    // Bar Chart: Pemeriksaan per Bulan
    const ctxBulan = document.getElementById('chartBulan').getContext('2d');
    new Chart(ctxBulan, {
        type: 'bar',
        data: {
            labels: [@foreach($chartBulan as $bulan => $count)'{{ $bulan }}',@endforeach],
            datasets: [{
                label: 'Jumlah Pemeriksaan',
                data: [{{ $chartBulan->implode(',') }}],
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Pie Chart: Distribusi Status Akhir
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: [@foreach($chartStatus as $label => $count)'{{ $label }}',@endforeach],
            datasets: [{
                data: [{{ implode(',', $chartStatus) }}],
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 16, usePointStyle: true }
                }
            }
        }
    });
</script>
@endsection
