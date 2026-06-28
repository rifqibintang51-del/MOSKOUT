<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOSKOUT - Portal Pemantauan Mandiri</title>
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
            --primary-dark: #4f46e5;
            --bg-gradient: linear-gradient(135deg, #eef2ff 0%, #e8edff 100%);
            --card-shadow: 0 10px 30px rgba(99, 102, 241, 0.05);
            --card-shadow-hover: 0 15px 35px rgba(99, 102, 241, 0.12);
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

        .hero-section {
            background: linear-gradient(180deg, #6366f1 0%, #3730a3 100%);
            color: #ffffff;
            padding: 5rem 0 7rem;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            margin-bottom: -4rem;
            position: relative;
            z-index: 1;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .hero-title {
            font-size: 2.75rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .grid-container {
            position: relative;
            z-index: 10;
        }

        .risk-card {
            background: #ffffff;
            border: 1px solid rgba(99, 102, 241, 0.08);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .risk-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .risk-badge {
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 0.5em 1em;
            border-radius: 30px;
            text-transform: uppercase;
        }

        .badge-tinggi {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .badge-sedang {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
        }

        .badge-rendah {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .risk-type-icon {
            font-size: 1.5rem;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-genangan { background: #e0f2fe; color: #0369a1; }
        .icon-barang-bekas { background: #ffedd5; color: #c2410c; }
        .icon-saluran-air { background: #dcfce7; color: #15803d; }
        .icon-tempat-sampah { background: #f3e8ff; color: #6b21a8; }

        .btn-detail {
            border-radius: 12px;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-detail:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .filter-bar {
            background: #ffffff;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(99, 102, 241, 0.08);
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
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
                        <a class="nav-link active" href="index.php"><i class="bi bi-geo-alt me-1"></i> Titik Risiko</a>
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

    <!-- Hero Section -->
    <header class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-primary fw-bold rounded-pill px-3 py-2 text-uppercase mb-3 shadow-sm">Portal Publik</span>
                    <h1 class="hero-title">Sistem Pemantauan Risiko DBD</h1>
                    <p class="lead opacity-75 mt-3">Mari bersama pantau kebersihan lingkungan, temukan titik jentik nyamuk Aedes aegypti, dan laporkan guna pencegahan dini DBD.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Filter Bar -->
    <main class="container grid-container my-5">
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

        <div id="errorContainer" class="alert alert-danger shadow-sm border-0 p-4 rounded-4 d-none" role="alert"></div>

        <div id="filterInfo" class="text-muted small mb-3" style="display:none;"></div>

        <div id="cardsContainer"></div>
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
    let allSpots = [];

    const ICON_MAP = {
        'barang bekas': { icon: 'bi-box-seam', theme: 'icon-barang-bekas' },
        'saluran air':  { icon: 'bi-water', theme: 'icon-saluran-air' },
        'tempat sampah':{ icon: 'bi-trash3', theme: 'icon-tempat-sampah' },
    };

    const STATUS_COLOR = {
        'aman': 'text-success',
        'perlu pemantauan': 'text-warning',
        'perlu tindakan': 'text-danger',
    };

    function esc(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function renderCount(spots) {
        const info = document.getElementById('filterInfo');
        if (spots.length !== allSpots.length) {
            info.style.display = 'block';
            info.innerHTML = '<i class="bi bi-funnel me-1"></i> Menampilkan <strong>' + spots.length + '</strong> dari <strong>' + allSpots.length + '</strong> titik risiko';
        } else {
            info.style.display = 'none';
        }
    }

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
        renderCards(filtered);
    }

    function renderCards(spots) {
        if (!spots || spots.length === 0) {
            renderCount(spots);
            document.getElementById('cardsContainer').innerHTML =
                '<div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">' +
                '<i class="bi bi-inbox text-primary" style="font-size:4rem;"></i>' +
                '<h5 class="fw-bold mt-3">' + (allSpots.length > 0 ? 'Tidak Ditemukan' : 'Belum Ada Lokasi Risiko') + '</h5>' +
                '<p class="text-muted mb-0">' + (allSpots.length > 0 ? 'Tidak ada titik risiko yang sesuai filter.' : 'Semua wilayah pantau sementara dilaporkan aman.') + '</p></div>';
            return;
        }

        renderCount(spots);
        let html = '<div class="row g-4">';
        spots.forEach(function (spot) {
            const m = ICON_MAP[spot.jenis_risiko] || { icon: 'bi-droplet', theme: 'icon-genangan' };
            const latest = spot.pemeriksaan_terakhir;
            const datePart = latest
                ? new Date(latest.tanggal_pemeriksaan + 'T00:00:00').toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })
                : '';

            let statusHtml;
            if (latest) {
                const sc = STATUS_COLOR[latest.status_akhir] || 'text-secondary';
                statusHtml =
                    '<strong class="d-block ' + sc + ' text-uppercase">' + esc(latest.status_akhir) + '</strong>' +
                    '<small class="text-muted-emphasis d-block mt-1">Ditemukan Jentik: <strong>' + esc(latest.ditemukan_jentik.toUpperCase()) + '</strong></small>' +
                    '<small class="text-muted d-block mt-1 font-monospace" style="font-size:0.75rem;">Dipantau: ' + datePart + '</small>';
            } else {
                statusHtml = '<strong class="d-block text-secondary text-uppercase">Belum Diperiksa</strong>';
            }

            html +=
                '<div class="col-12 col-md-6 col-lg-4">' +
                '<article class="risk-card p-4">' +
                '<div class="d-flex justify-content-between align-items-start mb-3">' +
                '<div class="risk-type-icon ' + m.theme + '"><i class="bi ' + m.icon + '"></i></div>' +
                '<span class="risk-badge badge-' + spot.level_risiko_awal + '">' + esc(spot.level_risiko_awal) + '</span>' +
                '</div>' +
                '<h5 class="fw-bold mb-1 text-truncate">' + esc(spot.nama_titik) + '</h5>' +
                '<p class="text-muted small mb-3 flex-grow-1"><i class="bi bi-geo-alt"></i> ' + esc(spot.alamat) + '</p>' +
                '<div class="bg-light rounded-3 p-3 mb-4 text-center">' +
                '<span class="text-muted d-block small mb-1">Kondisi Terakhir</span>' +
                statusHtml +
                '</div>' +
                '<div class="d-grid gap-2">' +
                '<a href="detail.php?id=' + spot.id + '" class="btn btn-outline-primary btn-detail">' +
                '<i class="bi bi-clock-history me-1"></i> Riwayat Laporan</a>' +
                '</div>' +
                '</article>' +
                '</div>';
        });
        html += '</div>';
        document.getElementById('cardsContainer').innerHTML = html;
    }

    async function loadData() {
        try {
            const res = await fetch(API + '/titik-risiko');
            const json = await res.json();

            document.getElementById('loading').classList.add('d-none');

            if (!json.success) {
                const err = document.getElementById('errorContainer');
                err.classList.remove('d-none');
                err.classList.add('d-flex', 'align-items-center', 'gap-3');
                err.innerHTML = '<i class="bi bi-exclamation-triangle-fill fs-2"></i><div><h5 class="fw-bold mb-1">Terjadi Kesalahan Koneksi API</h5><p class="mb-0 small">' + esc(json.message) + '</p></div>';
                return;
            }

            allSpots = json.data || [];
            document.getElementById('filterBar').style.display = 'block';
            applyFilters();
        } catch (err) {
            document.getElementById('loading').classList.add('d-none');
            const errDiv = document.getElementById('errorContainer');
            errDiv.classList.remove('d-none');
            errDiv.classList.add('d-flex', 'align-items-center', 'gap-3');
            errDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill fs-2"></i><div><h5 class="fw-bold mb-1">Terjadi Kesalahan Koneksi API</h5><p class="mb-0 small">Gagal terhubung ke API Server Laravel. Pastikan server berjalan di ' + API + '</p></div>';
        }
    }

    loadData();
    </script>
</body>
</html>
