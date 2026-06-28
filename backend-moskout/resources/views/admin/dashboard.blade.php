@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat 1: Total Titik Risiko -->
    <div class="col-12 col-md-6">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <span class="text-muted text-uppercase fw-bold small">Total Titik Risiko</span>
                <h2 class="mt-2 mb-0 fw-bold">{{ $totalTitik }}</h2>
                <p class="text-success small mb-0 mt-1"><i class="bi bi-geo-alt-fill"></i> Lokasi terdaftar</p>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                <i class="bi bi-geo-alt" style="font-size: 1.75rem;"></i>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Kasus Risiko Tinggi -->
    <div class="col-12 col-md-6">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between h-100">
            <div>
                <span class="text-muted text-uppercase fw-bold small">Kasus Risiko Tinggi</span>
                <h2 class="mt-2 mb-0 fw-bold text-danger">{{ $totalTinggi }}</h2>
                <p class="text-danger-emphasis small mb-0 mt-1"><i class="bi bi-exclamation-triangle-fill"></i> Butuh perhatian segera</p>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: rgba(244, 63, 94, 0.1); color: var(--secondary-color);">
                <i class="bi bi-exclamation-octagon" style="font-size: 1.75rem;"></i>
            </div>
        </div>
    </div>

</div>

<div class="row g-4">
    <!-- Recently Added Risk Spots -->
    <div class="col-12">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Titik Risiko Terbaru</h5>
                <a href="{{ route('admin.titik-risiko.index') }}" class="btn btn-light btn-sm fw-bold">Semua Data</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($titikTerbaru as $titik)
                    <div class="list-group-item bg-transparent px-0 py-3 border-bottom border-light-subtle">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $titik->nama_titik }}</h6>
                                <p class="text-muted small mb-0"><i class="bi bi-geo-alt"></i> {{ Str::limit($titik->alamat, 40) }}</p>
                            </div>
                            @php
                                $levelClass = [
                                    'tinggi' => 'bg-danger',
                                    'sedang' => 'bg-warning text-dark',
                                    'rendah' => 'bg-success'
                                ][$titik->level_risiko_awal] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $levelClass }}">{{ ucwords($titik->level_risiko_awal) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4 my-0">Belum ada lokasi risiko terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
