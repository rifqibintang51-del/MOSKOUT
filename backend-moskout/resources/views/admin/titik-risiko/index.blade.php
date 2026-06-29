@extends('layouts.admin')

@section('title', 'Kelola Titik Risiko')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-geo-alt text-primary"></i> Kelola Titik Risiko</h2>
        <p class="text-muted">Kelola seluruh lokasi titik pantau potensi berkembangnya jentik nyamuk DBD.</p>
    </div>
    <a href="{{ route('admin.titik-risiko.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Titik Risiko
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th style="width: 80px;">Foto</th>
                    <th>Nama Titik / Lokasi</th>
                    <th>Detail Alamat</th>
                    <th>Jenis Risiko</th>
                    <th class="text-center">Level Awal</th>
                    <th class="text-center">Status</th>
                    <th style="width: 150px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($titikRisikos as $index => $titik)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">
                            @if($titik->foto_url)
                                <img src="{{ $titik->foto_url }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" alt="Foto">
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $titik->nama_titik }}</div>
                            <small class="text-muted">RT/RW: {{ $titik->rt_rw ?? '-' }}</small>
                        </td>
                        <td>
                            <div>{{ Str::limit($titik->alamat, 60) }}</div>
                            @if($titik->provinsi || $titik->kabupaten || $titik->kecamatan || $titik->kelurahan)
                                <small class="text-muted d-block">
                                    {{ implode(' > ', array_filter([$titik->provinsi, $titik->kabupaten, $titik->kecamatan, $titik->kelurahan])) }}
                                </small>
                            @endif
                            <small class="text-muted-emphasis">GPS: {{ $titik->latitude }}, {{ $titik->longitude }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ ucwords($titik->jenis_risiko) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $levelClass = [
                                    'tinggi' => 'bg-danger',
                                    'sedang' => 'bg-warning text-dark',
                                    'rendah' => 'bg-success'
                                ][$titik->level_risiko_awal] ?? 'bg-secondary';
                            @endphp
                            <span class="badge badge-pill {{ $levelClass }}">
                                {{ strtoupper($titik->level_risiko_awal) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $titik->status_aktif ? 'bg-success' : 'bg-secondary' }}">
                                {{ $titik->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.titik-risiko.edit', $titik->id) }}" class="btn btn-sm btn-outline-primary" title="Ubah data">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.titik-risiko.destroy', $titik->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus titik risiko ini? Seluruh riwayat pemeriksaan terkait juga akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus data">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data titik risiko.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
