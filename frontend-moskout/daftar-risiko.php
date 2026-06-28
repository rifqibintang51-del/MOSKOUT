<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOSKOUT – Pemantauan Risiko DBD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bs-primary: #6366f1;
            --bs-primary-rgb: 99, 102, 241;
        }
        .filter-bar {
            background: #ffffff;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }
        .filter-bar .form-control,
        .filter-bar .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
        }
        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="daftar-risiko.php">
            <i class="bi bi-shield-exclamation"></i> MOSKOUT
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="daftar-risiko.php"><i class="bi bi-list-ul"></i> Titik Risiko</a></li>
                <li class="nav-item"><a class="nav-link" href="tambah-pemeriksaan.php"><i class="bi bi-plus-circle"></i> Input Pemeriksaan</a></li>
                <li class="nav-item"><a class="nav-link" href="#edukasi"><i class="bi bi-info-circle"></i> Edukasi</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary"><i class="bi bi-map"></i> Daftar Titik Risiko</h2>
            <p class="text-muted">Sistem Pemantauan Risiko Demam Berdarah Dengue (DBD) Berbasis Lingkungan.</p>
        </div>
        <a href="tambah-pemeriksaan.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Pemeriksaan
        </a>
    </div>

    <div class="filter-bar mb-4" id="filterBar" style="display:none;">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-search"></i> Cari Lokasi</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Nama atau alamat..." oninput="applyFilters()">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-activity"></i> Level Risiko</label>
                <select id="filterLevel" class="form-select" onchange="applyFilters()">
                    <option value="">Semua Level</option>
                    <option value="rendah">Rendah</option>
                    <option value="sedang">Sedang</option>
                    <option value="tinggi">Tinggi</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-tag"></i> Jenis Risiko</label>
                <select id="filterJenis" class="form-select" onchange="applyFilters()">
                    <option value="">Semua Jenis</option>
                    <option value="genangan">Genangan</option>
                    <option value="barang bekas">Barang Bekas</option>
                    <option value="saluran air">Saluran Air</option>
                    <option value="tempat sampah">Tempat Sampah</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-shield-check"></i> Status</label>
                <select id="filterStatus" class="form-select" onchange="applyFilters()">
                    <option value="">Semua</option>
                    <option value="aman">Aman</option>
                    <option value="perlu pemantauan">Perlu Pantau</option>
                    <option value="perlu tindakan">Perlu Tindakan</option>
                </select>
            </div>
        </div>
    </div>

    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Memuat data titik risiko...</p>
    </div>

    <div id="errorContainer" class="alert alert-danger d-none"></div>

    <div class="table-responsive d-none" id="tableWrapper">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary" style="--bs-table-bg: #eef2ff; --bs-table-color: #3730a3;">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Titik / Lokasi</th>
                    <th>Jenis Risiko</th>
                    <th>Level Risiko</th>
                    <th>Status Terakhir</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="risikoBody"></tbody>
        </table>
    </div>

    <div class="card border-primary mt-5 shadow-sm" id="edukasi" style="border-color: #6366f1 !important;">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-info-circle"></i> Edukasi Kesehatan: Gerakan 3M Plus Cegah DBD
        </div>
        <div class="card-body">
            <p class="lead">
                Demam Berdarah Dengue (DBD) disebabkan oleh virus dengue yang ditularkan melalui gigitan nyamuk
                <em>Aedes aegypti</em>. Pencegahan terbaik adalah dengan menerapkan
                <strong>Gerakan 3M Plus</strong> secara rutin dan berkelanjutan.
            </p>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <div class="card h-100 border-success shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-droplet-half text-success" style="font-size:2.5rem;"></i>
                            <h5 class="card-title mt-2">1. Menguras</h5>
                            <p class="card-text small">Kuras bak mandi, ember, dan tempat penampungan air minimal seminggu sekali. Gosok dindingnya agar telur nyamuk terlepas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-warning shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-box-seam text-warning" style="font-size:2.5rem;"></i>
                            <h5 class="card-title mt-2">2. Menutup</h5>
                            <p class="card-text small">Tutup rapat tempat penampungan air seperti drum, gentong, dan tandon air agar nyamuk tidak bisa bertelur.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-info shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-recycle text-info" style="font-size:2.5rem;"></i>
                            <h5 class="card-title mt-2">3. Memanfaatkan</h5>
                            <p class="card-text small">Daur ulang barang bekas yang berpotensi menjadi tempat genangan air, seperti kaleng, botol plastik, dan ban bekas.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-light rounded border">
                <h6 class="fw-bold text-primary"><i class="bi bi-plus-circle"></i> Plus (+):</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="mb-0 small">
                            <li>Menaburkan bubuk larvasida (<em>abate</em>) di tempat penampungan air.</li>
                            <li>Memelihara ikan pemakan jentik (ikan cupang, ikan mas).</li>
                            <li>Memasang kawat kasa pada ventilasi jendela dan pintu.</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0 small">
                            <li>Menggunakan lotion anti nyamuk dan kelambu saat tidur.</li>
                            <li>Menanam tanaman pengusir nyamuk (lavender, serai, zodia).</li>
                            <li>Melakukan gotong royong pembersihan lingkungan secara rutin.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = 'api_proxy.php';
let allSpots = [];

function esc(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}

function cap(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
}

const LEVEL_BADGE = {
    tinggi: 'bg-danger',
    sedang: 'bg-warning text-dark',
    rendah: 'bg-success',
};

const STATUS_BADGE = {
    aman: 'bg-success',
    'perlu pemantauan': 'bg-warning text-dark',
    'perlu tindakan': 'bg-danger',
};

function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const level = document.getElementById('filterLevel').value;
    const jenis = document.getElementById('filterJenis').value;
    const status = document.getElementById('filterStatus').value;

    const filtered = allSpots.filter(function (spot) {
        const matchSearch = !search ||
            spot.nama_titik.toLowerCase().includes(search) ||
            spot.alamat.toLowerCase().includes(search);
        const matchLevel = !level || spot.level_risiko_awal === level;
        const matchJenis = !jenis || spot.jenis_risiko === jenis;
        const matchStatus = !status ||
            (spot.pemeriksaan_terakhir && spot.pemeriksaan_terakhir.status_akhir === status);
        return matchSearch && matchLevel && matchJenis && matchStatus;
    });
    renderTable(filtered);
}

function renderTable(spots) {
    const tbody = document.getElementById('risikoBody');
    const countInfo = document.getElementById('filterCount');

    if (spots.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>' +
            (allSpots.length > 0 ? 'Tidak ada titik risiko yang sesuai filter.' : 'Belum ada data titik risiko.') + '</td></tr>';
        if (countInfo) countInfo.textContent = '0 dari ' + allSpots.length + ' titik';
        return;
    }

    let html = '';
    spots.forEach(function (t, i) {
        const levelBadge = LEVEL_BADGE[t.level_risiko_awal] || 'bg-secondary';
        const last = t.pemeriksaan_terakhir || null;
        const statusBadge = last ? (STATUS_BADGE[last.status_akhir] || 'bg-secondary') : 'bg-secondary';
        const statusLabel = last ? cap(last.status_akhir) : 'Belum Ada';
        const mapsLink = `https://www.google.com/maps?q=${t.latitude},${t.longitude}`;

        let alamatHtml = `<strong>${esc(t.nama_titik)}</strong><br><small class="text-muted">${esc(t.alamat)}</small>`;
        if (t.rt_rw) {
            alamatHtml += `<br><small class="text-muted">RT/RW: ${esc(t.rt_rw)}</small>`;
        }

        let statusHtml = `<span class="badge ${statusBadge}">${statusLabel}</span>`;
        if (last) {
            statusHtml += `<br><small class="text-muted">${esc(last.tanggal_pemeriksaan)}</small>`;
        }

        html +=
            '<tr>' +
            '<td class="text-center">' + (i + 1) + '</td>' +
            '<td>' + alamatHtml + '</td>' +
            '<td>' + esc(t.jenis_risiko) + '</td>' +
            '<td><span class="badge ' + levelBadge + ' fs-6">' + cap(t.level_risiko_awal) + '</span></td>' +
            '<td>' + statusHtml + '</td>' +
            '<td class="text-center">' +
            '<a href="' + mapsLink + '" target="_blank" class="btn btn-outline-primary btn-sm mb-1" title="Lihat di Google Maps">' +
            '<i class="bi bi-geo-alt"></i> Maps</a> ' +
            '<a href="detail-pemeriksaan.php?id=' + t.id + '" class="btn btn-outline-info btn-sm mb-1" title="Riwayat Pemeriksaan">' +
            '<i class="bi bi-clock-history"></i> Riwayat</a>' +
            '</td></tr>';
    });
    tbody.innerHTML = html;

    if (countInfo) {
        countInfo.textContent = 'Menampilkan ' + spots.length + ' dari ' + allSpots.length + ' titik';
    }
}

async function loadData() {
    try {
        const res = await fetch(API + '/titik-risiko');
        const json = await res.json();

        document.getElementById('loading').classList.add('d-none');

        if (!json.success) {
            const err = document.getElementById('errorContainer');
            err.classList.remove('d-none');
            err.textContent = json.message || 'Gagal memuat data.';
            return;
        }

        if (!json.data || json.data.length === 0) {
            document.getElementById('loading').innerHTML = '<p class="text-muted"><i class="bi bi-inbox"></i> Belum ada data titik risiko.</p>';
            document.getElementById('loading').classList.remove('d-none');
            return;
        }

        allSpots = json.data;
        document.getElementById('filterBar').style.display = 'block';
        document.getElementById('tableWrapper').classList.remove('d-none');

        const infoRow = document.createElement('div');
        infoRow.id = 'filterCount';
        infoRow.className = 'text-muted small mb-2';
        document.getElementById('tableWrapper').before(infoRow);

        applyFilters();
    } catch (err) {
        document.getElementById('loading').classList.add('d-none');
        const errDiv = document.getElementById('errorContainer');
        errDiv.classList.remove('d-none');
        errDiv.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Gagal terhubung ke server API. Pastikan server Laravel berjalan di <strong>http://localhost:8000</strong>.`;
    }
}

loadData();
</script>
</body>
</html>
