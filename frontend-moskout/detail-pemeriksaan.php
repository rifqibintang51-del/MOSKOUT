<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemeriksaan – MOSKOUT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bs-primary: #6366f1;
            --bs-primary-rgb: 99, 102, 241;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="daftar-risiko.php">
            <i class="bi bi-shield-exclamation"></i> MOSKOUT
        </a>
    </div>
</nav>

<div class="container py-4">
    <a href="daftar-risiko.php" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div id="containerDinamis"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = 'api_proxy.php';

function esc(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}

function cap(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
}

const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

if (!id) {
    document.getElementById('containerDinamis').innerHTML = `
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> ID titik risiko tidak ditemukan.</div>
    `;
} else {
    loadDetail(id);
}

async function loadDetail(titikId) {
    const container = document.getElementById('containerDinamis');
    container.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Memuat data...</p>
        </div>
    `;

    try {
        const res = await fetch(API + '/titik-risiko/' + titikId + '/pemeriksaan');
        const json = await res.json();

        if (!json.success || !json.titik_risiko) {
            container.innerHTML = `
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Titik risiko tidak ditemukan.</div>
            `;
            return;
        }

        const t = json.titik_risiko;
        const items = json.data || [];

        const levelBadge = {
            tinggi: 'bg-danger', sedang: 'bg-warning text-dark', rendah: 'bg-success'
        }[t.level_risiko_awal] || 'bg-secondary';

        const mapsLink = `https://www.google.com/maps?q=${t.latitude},${t.longitude}`;

        let alamatExtra = '';
        if (t.rt_rw) alamatExtra += `<p class="mb-1"><i class="bi bi-signpost-2"></i> RT/RW: ${esc(t.rt_rw)}</p>`;

        let html = `
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="fw-bold">${esc(t.nama_titik)}</h4>
                            <p class="mb-1"><i class="bi bi-geo-alt text-danger"></i> ${esc(t.alamat)}</p>
                            ${alamatExtra}
                        </div>
                        <div class="col-md-4 text-md-end">
                            <p class="mb-1"><strong>Jenis Risiko:</strong><br>${esc(t.jenis_risiko)}</p>
                            <p class="mb-0"><strong>Level Awal:</strong><br>
                                <span class="badge ${levelBadge} fs-6">${cap(t.level_risiko_awal)}</span>
                            </p>
                            <a href="${mapsLink}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="bi bi-geo-alt"></i> Buka Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        if (items.length === 0) {
            html += `<div class="alert alert-info"><i class="bi bi-info-circle"></i> Belum ada data pemeriksaan untuk titik ini.</div>`;
        } else {
            html += `<h5 class="fw-bold mb-3"><i class="bi bi-clock-history"></i> Riwayat Pemeriksaan</h5><div class="row">`;

            items.forEach(p => {
                const headerBg = {
                    aman: 'bg-success text-white',
                    'perlu pemantauan': 'bg-warning',
                    'perlu tindakan': 'bg-danger text-white'
                }[p.status_akhir] || '';

                const jentikBadge = p.ditemukan_jentik === 'ya' ? 'bg-danger' : 'bg-success';
                const jentikLabel = p.ditemukan_jentik === 'ya' ? 'Ya (Positif)' : 'Tidak (Negatif)';

                html += `
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center ${headerBg}">
                                <span><i class="bi bi-calendar3"></i> ${esc(p.tanggal_pemeriksaan)}</span>
                                <span class="badge bg-light text-dark">Petugas ID: ${esc(p.petugas_id)}</span>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <strong>Jentik:</strong>
                                    <span class="badge ${jentikBadge}">${jentikLabel}</span>
                                    <span class="badge float-end ${headerBg.split(' ')[0]}">${cap(p.status_akhir)}</span>
                                </p>
                                <p class="mb-1"><strong>Kondisi Lingkungan:</strong></p>
                                <p class="text-muted small">${esc(p.kondisi_lingkungan).replace(/\n/g, '<br>')}</p>
                                <p class="mb-1"><strong>Tindakan:</strong></p>
                                <p class="text-muted small">${esc(p.tindakan_dilakukan).replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;
        }

        container.innerHTML = html;
    } catch (err) {
        container.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Gagal terhubung ke server API. Pastikan server Laravel berjalan.
            </div>
        `;
    }
}
</script>
</body>
</html>
