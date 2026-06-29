<?php
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemeriksaan - MOSKOUT</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --bg-gradient: linear-gradient(135deg, #eef2ff 0%, #e8edff 100%);
            --card-shadow: 0 10px 30px rgba(99, 102, 241, 0.05);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-gradient);
            color: #212529;
            min-height: 100vh;
        }

        .navbar {
            background: rgba(99, 102, 241, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.15);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .glass-card {
            background: #ffffff;
            border: 1px solid rgba(99, 102, 241, 0.08);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .badge-tinggi { background-color: #f8d7da; color: #842029; }
        .badge-sedang { background-color: #fff3cd; color: #664d03; }
        .badge-rendah { background-color: #d1e7dd; color: #0f5132; }

        .btn-maps {
            background-color: #4285F4;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.2);
        }

        .btn-maps:hover {
            background-color: #357ae8;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(66, 133, 244, 0.3);
        }

        .table-responsive {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        .table th {
            background: #f8fafc;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: top;
        }

        footer {
            background-color: #1e293b;
            color: #94a3b8;
            padding: 3rem 0;
            margin-top: 5rem;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <i class="bi bi-shield-exclamation text-white"></i> MOSKOUT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-geo-alt me-1"></i> Titik Risiko</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="edukasi.php"><i class="bi bi-info-circle me-1"></i> Edukasi 3M Plus</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-light text-primary fw-bold rounded-pill px-4" href="/login"><i class="bi bi-lock-fill me-1"></i> Login Petugas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <a href="index.php" class="btn btn-light btn-sm mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>

        <div id="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat data pemeriksaan...</p>
        </div>

        <div id="errorContainer" class="alert alert-danger shadow-sm border-0 p-4 rounded-4 d-none" role="alert"></div>

        <div id="infoCard"></div>

        <h4 class="fw-bold mb-3" id="historyTitle" style="display:none;"><i class="bi bi-clock-history text-primary me-2"></i> Riwayat Pemeriksaan</h4>

        <div id="historyContainer"></div>
    </main>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <h5 class="fw-bold text-white mb-3">MOSKOUT</h5>
            <p class="small opacity-50">&copy; <?= date('Y') ?> KELOMPOK 8 WASPADA DBD. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const API = 'api_proxy.php';
    const titikId = <?= $id ?>;

    const STATUS_BADGE = {
        'aman': 'bg-success',
        'perlu pemantauan': 'bg-warning text-dark',
        'perlu tindakan': 'bg-danger',
    };

    function esc(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function cap(s) {
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function renderInfoCard(spot) {
        const levelBadgeClass = 'badge-' + spot.level_risiko_awal;
        document.getElementById('infoCard').innerHTML =
            '<div class="glass-card">' +
            '<div class="row g-4 align-items-center">' +
            '<div class="col-md-8">' +
            '<span class="badge bg-primary text-white mb-2 rounded-pill px-3 py-1 text-uppercase" style="font-size:0.7rem;">Informasi Lokasi</span>' +
            '<h2 class="fw-bold mb-1">' + esc(spot.nama_titik) + '</h2>' +
            '<p class="text-muted mb-2"><i class="bi bi-geo-alt-fill text-primary"></i> ' + esc(spot.alamat) + '</p>' +
            '<div class="d-flex flex-wrap gap-3 mt-3">' +
            '<div class="bg-light px-3 py-2 rounded-3">' +
            '<span class="text-muted d-block small" style="font-size:0.75rem;">RT/RW</span>' +
            '<strong>' + esc(spot.rt_rw || '-') + '</strong></div>' +
            '<div class="bg-light px-3 py-2 rounded-3">' +
            '<span class="text-muted d-block small" style="font-size:0.75rem;">Jenis Risiko</span>' +
            '<strong>' + cap(esc(spot.jenis_risiko)) + '</strong></div>' +
            '<div class="bg-light px-3 py-2 rounded-3">' +
            '<span class="text-muted d-block small" style="font-size:0.75rem;">Level Risiko Awal</span>' +
            '<span class="badge ' + levelBadgeClass + ' text-uppercase mt-1">' + esc(spot.level_risiko_awal) + '</span></div>' +
            '</div></div>' +
            '<div class="col-md-4 text-md-end">' +
            '<a href="https://www.google.com/maps?q=' + spot.latitude + ',' + spot.longitude + '" target="_blank" class="btn btn-maps w-100 py-3">' +
            '<i class="bi bi-map-fill me-2"></i> Buka Google Maps</a>' +
            '<small class="text-muted mt-2 d-block text-center font-monospace" style="font-size:0.75rem;">' +
            'GPS: ' + spot.latitude + ', ' + spot.longitude + '</small></div></div></div>';
    }

    function renderHistory(history) {
        const title = document.getElementById('historyTitle');
        const container = document.getElementById('historyContainer');

        if (!history || history.length === 0) {
            title.style.display = 'block';
            container.innerHTML =
                '<div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">' +
                '<i class="bi bi-info-circle text-muted" style="font-size:3rem;"></i>' +
                '<h5 class="fw-bold mt-3 text-muted">Belum Ada Riwayat Pemeriksaan</h5>' +
                '<p class="text-muted mb-0 small">Belum ada kunjungan petugas ke lokasi ini untuk periode ini.</p></div>';
            return;
        }

        title.style.display = 'block';
        let rows = '';
        history.forEach(function (pem) {
            const sClass = STATUS_BADGE[pem.status_akhir] || 'bg-secondary';
            const jentikBadge = pem.ditemukan_jentik === 'ya' ? 'bg-danger' : 'bg-success';
            rows +=
                '<tr>' +
                '<td class="fw-bold">' + formatDate(pem.tanggal_pemeriksaan) +
                '<small class="text-muted d-block" style="font-size:0.7rem;">Petugas ID: ' + esc(pem.petugas_id) + '</small></td>' +
                '<td class="text-center"><span class="badge ' + jentikBadge + '">' + esc(pem.ditemukan_jentik.toUpperCase()) + '</span></td>' +
                '<td><span class="small d-block text-wrap">' + esc(pem.kondisi_lingkungan) + '</span></td>' +
                '<td><span class="small d-block text-wrap">' + esc(pem.tindakan_dilakukan) + '</span></td>' +
                '<td class="text-center"><span class="badge rounded-pill px-3 py-2 text-uppercase ' + sClass + '" style="font-size:0.75rem;">' + esc(pem.status_akhir) + '</span></td>' +
                '<td class="text-center">' + (pem.foto_url ? '<img src="' + esc(pem.foto_url) + '" style="width:60px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer;" onclick="window.open(\'' + esc(pem.foto_url) + '\')">' : '<span class="text-muted">-</span>') + '</td>' +
                '</tr>';
        });
        container.innerHTML =
            '<div class="table-responsive shadow-sm">' +
            '<table class="table table-hover align-middle mb-0">' +
            '<thead><tr>' +
            '<th style="width:150px;">Tanggal</th><th style="width:100px;" class="text-center">Jentik</th>' +
            '<th>Kondisi Lingkungan</th><th>Tindakan Dilakukan</th>' +
            '<th style="width:180px;" class="text-center">Status Akhir</th>' +
            '<th style="width:80px;" class="text-center">Foto</th>' +
            '</tr></thead><tbody>' + rows + '</tbody></table></div>';
    }

    async function loadData() {
        try {
            const res = await fetch(API + '/titik-risiko/' + titikId + '/pemeriksaan');
            const json = await res.json();

            document.getElementById('loading').classList.add('d-none');

            if (!json.success) {
                const err = document.getElementById('errorContainer');
                err.classList.remove('d-none');
                err.classList.add('d-flex', 'align-items-center', 'gap-3');
                err.innerHTML = '<i class="bi bi-exclamation-triangle-fill fs-2"></i><div><h5 class="fw-bold mb-1">Gagal Memuat Laporan</h5><p class="mb-0 small">' + esc(json.message) + '</p></div>';
                return;
            }

            const spot = json.titik_risiko;
            if (!spot) {
                const err = document.getElementById('errorContainer');
                err.classList.remove('d-none');
                err.classList.add('d-flex', 'align-items-center', 'gap-3');
                err.innerHTML =
                    '<i class="bi bi-exclamation-circle me-2"></i><div><h5 class="fw-bold mb-1">Data Tidak Ditemukan</h5><p class="mb-0 small">Titik risiko yang Anda cari mungkin tidak aktif atau telah dihapus.</p></div>';
                return;
            }

            renderInfoCard(spot);
            renderHistory(json.data);
        } catch (err) {
            document.getElementById('loading').classList.add('d-none');
            const errDiv = document.getElementById('errorContainer');
            errDiv.classList.remove('d-none');
            errDiv.classList.add('d-flex', 'align-items-center', 'gap-3');
            errDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill fs-2"></i><div><h5 class="fw-bold mb-1">Gagal Memuat Laporan</h5><p class="mb-0 small">Gagal terhubung ke API Server Laravel. Pastikan server berjalan di ' + API + '</p></div>';
        }
    }

    loadData();
    </script>
</body>
</html>
