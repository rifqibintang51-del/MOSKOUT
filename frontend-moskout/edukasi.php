<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edukasi 3M Plus - MOSKOUT</title>
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

        .edu-card {
            background: #ffffff;
            border: none;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            border-left: 5px solid var(--primary);
        }

        .step-icon {
            font-size: 2.5rem;
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .step-1 { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .step-2 { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
        .step-3 { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }

        .plus-item {
            background-color: #fff;
            border: 1px solid rgba(99, 102, 241, 0.08);
            border-radius: 16px;
            padding: 1.25rem;
            height: 100%;
            transition: transform 0.2s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .plus-item:hover {
            transform: translateY(-3px);
            border-color: rgba(99, 102, 241, 0.2);
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
                        <a class="nav-link active" href="edukasi.php"><i class="bi bi-info-circle me-1"></i> Edukasi 3M Plus</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-light text-primary fw-bold rounded-pill px-4" href="/login"><i class="bi bi-lock-fill me-1"></i> Login Petugas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <span class="badge bg-primary text-white mb-2 rounded-pill px-3 py-1 text-uppercase" style="font-size: 0.75rem;">Edukasi Kesehatan</span>
                <h1 class="fw-bold display-5">Cegah Demam Berdarah dengan 3M Plus</h1>
                <p class="text-muted lead">Langkah sederhana yang bisa menyelamatkan nyawa keluarga dan tetangga Anda dari bahaya gigitan nyamuk Aedes aegypti.</p>
            </div>
        </div>

        <!-- 3M Core Concept -->
        <div class="row g-4 mb-5">
            <!-- M1 -->
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm p-4 rounded-4 bg-white text-center">
                    <div class="step-icon step-1 mx-auto">
                        <i class="bi bi-droplet-half"></i>
                    </div>
                    <h4 class="fw-bold mb-2">1. Menguras</h4>
                    <p class="text-muted small mb-0">Menguras dan membersihkan wadah air seperti bak mandi, kendi, vas bunga, ember, penampung air kulkas secara rutin minimal satu kali dalam seminggu.</p>
                </div>
            </div>
            <!-- M2 -->
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm p-4 rounded-4 bg-white text-center">
                    <div class="step-icon step-2 mx-auto">
                        <i class="bi bi-box"></i>
                    </div>
                    <h4 class="fw-bold mb-2">2. Menutup</h4>
                    <p class="text-muted small mb-0">Menutup rapat semua wadah penampungan air seperti tandon, gentong, dan drum penyimpanan air bersih agar nyamuk betina dewasa tidak dapat masuk dan bertelur.</p>
                </div>
            </div>
            <!-- M3 -->
            <div class="col-md-4">
                <div class="card border-0 h-100 shadow-sm p-4 rounded-4 bg-white text-center">
                    <div class="step-icon step-3 mx-auto">
                        <i class="bi bi-recycle"></i>
                    </div>
                    <h4 class="fw-bold mb-2">3. Mendaur Ulang</h4>
                    <p class="text-muted small mb-0">Memanfaatkan kembali atau mendaur ulang barang-barang bekas (seperti botol, kaleng, ban) yang berpotensi menampung air hujan dan menjadi sarang nyamuk.</p>
                </div>
            </div>
        </div>

        <!-- The "Plus" section -->
        <div class="edu-card">
            <h3 class="fw-bold mb-2"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Langkah Plus (+) Pencegahan Tambahan</h3>
            <p class="text-muted">Selain gerakan dasar 3M, lakukan juga tindakan pelengkap berikut demi perlindungan menyeluruh:</p>
            
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="plus-item d-flex gap-3 align-items-start">
                        <i class="bi bi-shield-check text-primary fs-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Pasang Kawat Kasa</h6>
                            <p class="text-muted small mb-0">Memasang kawat kasa nyamuk pada ventilasi jendela dan lubang angin rumah.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="plus-item d-flex gap-3 align-items-start">
                        <i class="bi bi-bug text-primary fs-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Gunakan Bubuk Larvasida</h6>
                            <p class="text-muted small mb-0">Menaburkan bubuk larvasida (abate) di bak mandi atau penampungan air yang sulit dibersihkan.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="plus-item d-flex gap-3 align-items-start">
                        <i class="bi bi-flower1 text-primary fs-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Tanam Tumbuhan Pengusir Nyamuk</h6>
                            <p class="text-muted small mb-0">Menanam tanaman hias alami pengusir nyamuk seperti serai, lavender, rosemary, atau zodia.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="plus-item d-flex gap-3 align-items-start">
                        <i class="bi bi-patch-exclamation text-primary fs-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Hindari Menggantung Baju</h6>
                            <p class="text-muted small mb-0">Menghindari kebiasaan menggantung pakaian kotor di balik pintu yang dapat menjadi tempat peristirahatan nyamuk.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jentik Nyamuk Aedes aegypti -->
        <div class="row g-4 align-items-center mt-4 bg-white rounded-4 p-4 shadow-sm">
            <div class="col-md-7">
                <h3 class="fw-bold">Kenali Ciri Nyamuk Aedes aegypti</h3>
                <p class="text-muted mt-3">Nyamuk penular demam berdarah memiliki ciri-ciri khusus yang membedakannya dengan nyamuk biasa:</p>
                <ul class="text-muted small ps-3">
                    <li class="mb-2"><strong>Warna Tubuh:</strong> Memiliki belang-belang hitam-putih di seluruh tubuh dan kaki.</li>
                    <li class="mb-2"><strong>Waktu Menggigit:</strong> Aktif menghisap darah pada pagi hari (pukul 08.00 - 10.00) dan sore hari (pukul 15.00 - 17.00).</li>
                    <li class="mb-2"><strong>Tempat Berkembang Biak:</strong> Hanya dapat bertelur di air yang bersih dan tenang (bukan tanah/got berlumpur).</li>
                    <li class="mb-2"><strong>Jarak Terbang:</strong> Mampu terbang sejauh 100 - 200 meter dari tempat penetasan telurnya.</li>
                </ul>
            </div>
            <div class="col-md-5 bg-light rounded-3 p-4 text-center border">
                <i class="bi bi-info-square text-primary" style="font-size: 3rem;"></i>
                <h5 class="fw-bold mt-2">Penting Diketahui</h5>
                <p class="text-muted small mb-0">Telur nyamuk Aedes aegypti dapat bertahan hidup di tempat kering selama lebih dari 6 bulan dan akan menetas menjadi jentik kembali setelah tergenang air.</p>
            </div>
        </div>
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
</body>
</html>
