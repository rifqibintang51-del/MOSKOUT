@extends('layouts.petugas')

@section('title', 'Riwayat Pemeriksaan Risiko')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-clipboard2-check" style="color: var(--primary-color);"></i> Riwayat Pemeriksaan</h2>
        <p class="text-muted">Kelola seluruh riwayat laporan pemeriksaan jentik nyamuk dan tindakan lapangan.</p>
    </div>
    <a href="{{ route('petugas.pemeriksaan-risiko.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Input Hasil Pemeriksaan
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
                    <th>Titik Risiko</th>
                    <th>Info Petugas & Tanggal</th>
                    <th class="text-center">Revisi</th>
                    <th class="text-center">Ditemukan Jentik</th>
                    <th>Kondisi Lingkungan & Tindakan</th>
                    <th class="text-center">Status Akhir</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemeriksaans as $index => $pem)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">
                            @if($pem->foto_url)
                                <img src="{{ $pem->foto_url }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" alt="Foto">
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($pem->titikRisiko)
                                <div class="fw-bold">{{ $pem->titikRisiko->nama_titik }}</div>
                                <small class="text-muted">{{ Str::limit($pem->titikRisiko->alamat, 40) }}</small>
                            @else
                                <span class="text-danger small">Titik Risiko Dihapus</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-semibold"><i class="bi bi-person me-1"></i>Petugas ID: {{ $pem->petugas_id }}</div>
                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($pem->tanggal_pemeriksaan)->format('d F Y') }}</small>
                        </td>
                        <td class="text-center">
                            @if($pem->revisi_ke > 1)
                                <span class="badge bg-info text-dark px-2 py-1" title="Revisi ke-{{ $pem->revisi_ke }}">
                                    <i class="bi bi-arrow-repeat me-1"></i>Rev {{ $pem->revisi_ke }}
                                </span>
                            @else
                                <span class="badge bg-secondary px-2 py-1" title="Data awal">
                                    <i class="bi bi-file-text me-1"></i>Awal
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $pem->ditemukan_jentik == 'ya' ? 'bg-danger' : 'bg-success' }} px-2 py-1">
                                {{ strtoupper($pem->ditemukan_jentik) }}
                            </span>
                        </td>
                        <td>
                            <div class="small"><strong>Lingkungan:</strong> {{ Str::limit($pem->kondisi_lingkungan, 60) }}</div>
                            <div class="small text-muted"><strong>Tindakan:</strong> {{ Str::limit($pem->tindakan_dilakukan, 60) }}</div>
                        </td>
                        <td class="text-center">
                            @php
                                $statusClass = [
                                    'aman' => 'bg-success',
                                    'perlu pemantauan' => 'bg-warning text-dark',
                                    'perlu tindakan' => 'bg-danger'
                                ][$pem->status_akhir] ?? 'bg-secondary';
                            @endphp
                            <span class="badge badge-pill {{ $statusClass }}">
                                {{ ucwords($pem->status_akhir) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('petugas.pemeriksaan-risiko.edit', $pem->id) }}" class="btn btn-sm btn-outline-primary" title="Ubah data">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('petugas.pemeriksaan-risiko.destroy', $pem->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pemeriksaan ini?');">
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
                        <td colspan="9" class="text-center text-muted py-4">Belum ada riwayat laporan pemeriksaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
