@extends('layouts.petugas')

@section('title', 'Input Pemeriksaan Risiko')

@section('content')
<div class="mb-4">
    <a href="{{ route('petugas.pemeriksaan-risiko.index') }}" class="btn btn-light btn-sm mb-2"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
    <h2 class="fw-bold">Input Laporan Pemeriksaan</h2>
    <p class="text-muted">Laporkan hasil survei lapangan kondisi jentik dan kebersihan lingkungan.</p>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="glass-card p-4">
            <form action="{{ route('petugas.pemeriksaan-risiko.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="titik_risiko_id" class="form-label">Titik Risiko / Lokasi Pantau <span class="text-danger">*</span></label>
                    <select name="titik_risiko_id" id="titik_risiko_id" class="form-select @error('titik_risiko_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Titik Risiko --</option>
                        @foreach($titikRisikos as $titik)
                            <option value="{{ $titik->id }}" {{ old('titik_risiko_id') == $titik->id ? 'selected' : '' }}>
                                {{ $titik->nama_titik }} ({{ $titik->alamat }})
                            </option>
                        @endforeach
                    </select>
                    @error('titik_risiko_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="petugas_id" class="form-label">ID Petugas <span class="text-danger">*</span></label>
                        <input type="number" name="petugas_id" id="petugas_id" class="form-control @error('petugas_id') is-invalid @enderror" value="{{ old('petugas_id', Auth::user()->id) }}" min="1" required>
                        @error('petugas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pemeriksaan" id="tanggal_pemeriksaan" class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror" value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                        @error('tanggal_pemeriksaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="ditemukan_jentik" class="form-label">Ditemukan Jentik Nyamuk? <span class="text-danger">*</span></label>
                        <select name="ditemukan_jentik" id="ditemukan_jentik" class="form-select @error('ditemukan_jentik') is-invalid @enderror" required>
                            <option value="">-- Pilih Status Jentik --</option>
                            <option value="ya" {{ old('ditemukan_jentik') == 'ya' ? 'selected' : '' }}>Ya (Positif Jentik)</option>
                            <option value="tidak" {{ old('ditemukan_jentik') == 'tidak' ? 'selected' : '' }}>Tidak (Negatif Jentik)</option>
                        </select>
                        @error('ditemukan_jentik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status_akhir" class="form-label">Status Akhir Lokasi <span class="text-danger">*</span></label>
                        <select name="status_akhir" id="status_akhir" class="form-select @error('status_akhir') is-invalid @enderror" required>
                            <option value="">-- Pilih Status Akhir --</option>
                            <option value="aman" {{ old('status_akhir') == 'aman' ? 'selected' : '' }}>Aman</option>
                            <option value="perlu pemantauan" {{ old('status_akhir') == 'perlu pemantauan' ? 'selected' : '' }}>Perlu Pemantauan</option>
                            <option value="perlu tindakan" {{ old('status_akhir') == 'perlu tindakan' ? 'selected' : '' }}>Perlu Tindakan</option>
                        </select>
                        @error('status_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kondisi_lingkungan" class="form-label">Deskripsi Kondisi Lingkungan <span class="text-danger">*</span></label>
                    <textarea name="kondisi_lingkungan" id="kondisi_lingkungan" rows="3" class="form-control @error('kondisi_lingkungan') is-invalid @enderror" placeholder="Jelaskan kondisi kebersihan, genangan air, atau tumpukan barang bekas..." required minlength="10">{{ old('kondisi_lingkungan') }}</textarea>
                    @error('kondisi_lingkungan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tindakan_dilakukan" class="form-label">Tindakan Lapangan yang Dilakukan <span class="text-danger">*</span></label>
                    <textarea name="tindakan_dilakukan" id="tindakan_dilakukan" rows="3" class="form-control @error('tindakan_dilakukan') is-invalid @enderror" placeholder="Contoh: Menguras wadah air, pemberian bubuk larvasida/abate, gotong royong warga..." required minlength="5">{{ old('tindakan_dilakukan') }}</textarea>
                    @error('tindakan_dilakukan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="bi bi-save me-1"></i> Simpan Hasil Pemeriksaan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
