@extends('layouts.admin')

@section('title', 'Tambah Titik Risiko')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        z-index: 1;
    }
    .map-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 400px;
        background: #f1f5f9;
        border-radius: 12px;
        color: var(--text-light);
    }
    .geocode-status {
        font-size: 0.8rem;
        margin-top: 0.5rem;
        min-height: 1.2rem;
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.titik-risiko.index') }}" class="btn btn-light btn-sm mb-2"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
    <h2 class="fw-bold">Tambah Titik Risiko</h2>
    <p class="text-muted">Masukkan data titik risiko potensi DBD baru untuk dipantau secara berkala.</p>
</div>

<div class="row">
    <div class="col-12 col-lg-7">
        <div class="glass-card p-4">
            <form action="{{ route('admin.titik-risiko.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_titik" class="form-label">Nama Titik / Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="nama_titik" id="nama_titik" class="form-control @error('nama_titik') is-invalid @enderror" placeholder="Contoh: TPS Sukun / Saluran Air RT 02" value="{{ old('nama_titik') }}" required>
                    @error('nama_titik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" placeholder="Klik pada peta untuk mengisi alamat otomatis, atau ketik manual..." required>{{ old('alamat') }}</textarea>
                    <div class="geocode-status text-muted" id="geocode-status"></div>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Wilayah Administratif</label>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <select name="provinsi" id="provinsi" class="form-select @error('provinsi') is-invalid @enderror">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                            @error('provinsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <select name="kabupaten" id="kabupaten" class="form-select @error('kabupaten') is-invalid @enderror" disabled>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                            @error('kabupaten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <select name="kecamatan" id="kecamatan" class="form-select @error('kecamatan') is-invalid @enderror" disabled>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <select name="kelurahan" id="kelurahan" class="form-select @error('kelurahan') is-invalid @enderror" disabled>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                            @error('kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="rt_rw" class="form-label">RT/RW</label>
                        <input type="text" name="rt_rw" id="rt_rw" class="form-control @error('rt_rw') is-invalid @enderror" placeholder="Contoh: 003/004" value="{{ old('rt_rw') }}">
                        @error('rt_rw')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_risiko" class="form-label">Jenis Risiko <span class="text-danger">*</span></label>
                        <select name="jenis_risiko" id="jenis_risiko" class="form-select @error('jenis_risiko') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Risiko --</option>
                            <option value="genangan" {{ old('jenis_risiko') == 'genangan' ? 'selected' : '' }}>Genangan Air</option>
                            <option value="barang bekas" {{ old('jenis_risiko') == 'barang bekas' ? 'selected' : '' }}>Barang Bekas</option>
                            <option value="saluran air" {{ old('jenis_risiko') == 'saluran air' ? 'selected' : '' }}>Saluran Air / Selokan</option>
                            <option value="tempat sampah" {{ old('jenis_risiko') == 'tempat sampah' ? 'selected' : '' }}>Tempat Pembuangan Sampah</option>
                        </select>
                        @error('jenis_risiko')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="latitude" class="form-label">Latitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="Klik peta untuk mengisi" value="{{ old('latitude') }}" required readonly>
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="longitude" class="form-label">Longitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="Klik peta untuk mengisi" value="{{ old('longitude') }}" required readonly>
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="level_risiko_awal" class="form-label">Level Risiko Awal <span class="text-danger">*</span></label>
                        <select name="level_risiko_awal" id="level_risiko_awal" class="form-select @error('level_risiko_awal') is-invalid @enderror" required>
                            <option value="">-- Pilih Level Risiko --</option>
                            <option value="rendah" {{ old('level_risiko_awal') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ old('level_risiko_awal') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ old('level_risiko_awal') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                        @error('level_risiko_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status_aktif" class="form-label">Status Aktif <span class="text-danger">*</span></label>
                        <select name="status_aktif" id="status_aktif" class="form-select @error('status_aktif') is-invalid @enderror" required>
                            <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status_aktif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="bi bi-save me-1"></i> Simpan Titik Risiko
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-12 col-lg-5 mt-4 mt-lg-0">
        <!-- Interactive Map -->
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-1"><i class="bi bi-map text-primary me-1"></i> Pilih Lokasi di Peta</h5>
            <p class="text-muted small mb-3">Klik pada peta untuk menentukan titik lokasi. Koordinat dan alamat akan terisi otomatis.</p>
            <div id="map"></div>
        </div>
        
        <!-- Info Card -->
        <div class="glass-card p-4 mt-3">
            <h6 class="fw-bold"><i class="bi bi-info-circle text-primary me-1"></i> Panduan</h6>
            <ul class="small text-muted ps-3 mb-0">
                <li>Klik langsung di peta untuk memilih lokasi</li>
                <li>Gunakan scroll untuk zoom in/out</li>
                <li>Drag peta untuk melihat area lain</li>
                <li>Koordinat & alamat akan terisi otomatis</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map centered on Malang
        const map = L.map('map').setView([-7.9666, 112.6326], 13);

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);

        let marker = null;
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const alamatInput = document.getElementById('alamat');
        const statusEl = document.getElementById('geocode-status');

        // If old values exist (validation error), place marker
        if (latInput.value && lngInput.value) {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], 16);
        }

        // Click event on map
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Update form fields
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);

            // Remove readonly so form can submit
            latInput.removeAttribute('readonly');
            lngInput.removeAttribute('readonly');

            // Place or move marker
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Reverse geocode
            reverseGeocode(lat, lng);
        });

        function reverseGeocode(lat, lng) {
            statusEl.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Mengambil alamat...';
            statusEl.className = 'geocode-status text-info';

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=id`, {
                headers: {
                    'User-Agent': 'MOSKOUT-WaspadaDBD/1.0'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    alamatInput.value = data.display_name;
                    statusEl.innerHTML = '<i class="bi bi-check-circle me-1"></i> Alamat berhasil diambil dari peta';
                    statusEl.className = 'geocode-status text-success';
                    
                    // Auto-fade status after 3 seconds
                    setTimeout(() => {
                        statusEl.innerHTML = '';
                    }, 3000);
                } else {
                    statusEl.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Alamat tidak ditemukan, silakan ketik manual';
                    statusEl.className = 'geocode-status text-warning';
                }
            })
            .catch(error => {
                console.error('Geocode error:', error);
                statusEl.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Gagal mengambil alamat. Ketik manual.';
                statusEl.className = 'geocode-status text-danger';
            });
        }

        // Fix map rendering issue when in hidden/animated container
        setTimeout(() => { map.invalidateSize(); }, 300);
    });

    // === Cascading Wilayah Dropdown ===
    const WILAYAH_API = '/admin/wilayah';
    const provinsiEl = document.getElementById('provinsi');
    const kabupatenEl = document.getElementById('kabupaten');
    const kecamatanEl = document.getElementById('kecamatan');
    const kelurahanEl = document.getElementById('kelurahan');
    const savedProvinsi = @json(old('provinsi'));
    const savedKabupaten = @json(old('kabupaten'));
    const savedKecamatan = @json(old('kecamatan'));
    const savedKelurahan = @json(old('kelurahan'));

    async function fetchJSON(url) {
        const res = await fetch(url);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    }

    function populateSelect(select, data, selectedValue) {
        select.innerHTML = '<option value="">-- Pilih ' + select.getAttribute('data-label') + ' --</option>';
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.name;
            opt.textContent = item.name;
            opt.dataset.id = item.id;
            if (item.name === selectedValue) opt.selected = true;
            select.appendChild(opt);
        });
        select.disabled = false;
    }

    function resetSelect(select) {
        select.innerHTML = '<option value="">-- Pilih ' + select.getAttribute('data-label') + ' --</option>';
        select.disabled = true;
    }

    provinsiEl.dataset.label = 'Provinsi';
    kabupatenEl.dataset.label = 'Kabupaten/Kota';
    kecamatanEl.dataset.label = 'Kecamatan';
    kelurahanEl.dataset.label = 'Kelurahan/Desa';

    async function loadWilayah() {
        try {
            const provinces = await fetchJSON(WILAYAH_API + '/provinces');
            populateSelect(provinsiEl, provinces, savedProvinsi);

            if (savedProvinsi) {
                const pid = provinsiEl.options[provinsiEl.selectedIndex]?.dataset.id;
                if (pid) {
                    const regencies = await fetchJSON(WILAYAH_API + '/regencies/' + pid);
                    populateSelect(kabupatenEl, regencies, savedKabupaten);
                }
                if (savedKabupaten) {
                    const rid = kabupatenEl.options[kabupatenEl.selectedIndex]?.dataset.id;
                    if (rid) {
                        const districts = await fetchJSON(WILAYAH_API + '/districts/' + rid);
                        populateSelect(kecamatanEl, districts, savedKecamatan);
                    }
                    if (savedKecamatan) {
                        const did = kecamatanEl.options[kecamatanEl.selectedIndex]?.dataset.id;
                        if (did) {
                            const villages = await fetchJSON(WILAYAH_API + '/villages/' + did);
                            populateSelect(kelurahanEl, villages, savedKelurahan);
                        }
                    }
                }
            }
        } catch (e) {
            console.warn('Wilayah data not available:', e.message);
        }
    }

    provinsiEl.addEventListener('change', async function() {
        const pid = this.options[this.selectedIndex]?.dataset.id;
        resetSelect(kabupatenEl);
        resetSelect(kecamatanEl);
        resetSelect(kelurahanEl);
        if (pid) {
            try {
                const regencies = await fetchJSON(WILAYAH_API + '/regencies/' + pid);
                populateSelect(kabupatenEl, regencies);
            } catch (e) {
                console.warn('Regencies not available:', e.message);
            }
        }
    });

    kabupatenEl.addEventListener('change', async function() {
        const rid = this.options[this.selectedIndex]?.dataset.id;
        resetSelect(kecamatanEl);
        resetSelect(kelurahanEl);
        if (rid) {
            try {
                const districts = await fetchJSON(WILAYAH_API + '/districts/' + rid);
                populateSelect(kecamatanEl, districts);
            } catch (e) {
                console.warn('Districts not available:', e.message);
            }
        }
    });

    kecamatanEl.addEventListener('change', async function() {
        const did = this.options[this.selectedIndex]?.dataset.id;
        resetSelect(kelurahanEl);
        if (did) {
            try {
                const villages = await fetchJSON(WILAYAH_API + '/villages/' + did);
                populateSelect(kelurahanEl, villages);
            } catch (e) {
                console.warn('Villages not available:', e.message);
            }
        }
    });

    loadWilayah();
</script>
@endsection
