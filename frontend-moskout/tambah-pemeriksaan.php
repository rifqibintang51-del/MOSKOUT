<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pemeriksaan – MOSKOUT</title>
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
        <a class="navbar-brand fw-bold" href="daftar-risiko.php"><i class="bi bi-shield-exclamation"></i> MOSKOUT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="daftar-risiko.php"><i class="bi bi-list-ul"></i> Titik Risiko</a></li>
                <li class="nav-item"><a class="nav-link active" href="tambah-pemeriksaan.php"><i class="bi bi-plus-circle"></i> Input Pemeriksaan</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <a href="daftar-risiko.php" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-clipboard-plus"></i> Form Input Pemeriksaan Risiko
                </div>
                <div class="card-body">

                    <div id="alertContainer"></div>

                    <form id="formPemeriksaan" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Titik Risiko <span class="text-danger">*</span></label>
                            <select name="titik_risiko_id" class="form-select" id="titikSelect" required>
                                <option value="">-- Memuat data titik risiko... --</option>
                            </select>
                            <div class="invalid-feedback" id="err_titik_risiko_id"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Petugas ID <span class="text-danger">*</span></label>
                                <input type="number" name="petugas_id" class="form-control" min="1" required>
                                <div class="invalid-feedback" id="err_petugas_id"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pemeriksaan" class="form-control" required max="">
                                <div class="invalid-feedback" id="err_tanggal_pemeriksaan"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ditemukan Jentik <span class="text-danger">*</span></label>
                                <select name="ditemukan_jentik" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>
                                <div class="invalid-feedback" id="err_ditemukan_jentik"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Akhir <span class="text-danger">*</span></label>
                                <select name="status_akhir" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="aman">Aman</option>
                                    <option value="perlu pemantauan">Perlu Pemantauan</option>
                                    <option value="perlu tindakan">Perlu Tindakan</option>
                                </select>
                                <div class="invalid-feedback" id="err_status_akhir"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kondisi Lingkungan <span class="text-danger">*</span></label>
                            <textarea name="kondisi_lingkungan" class="form-control" rows="3" minlength="10" required placeholder="Deskripsikan kondisi lingkungan sekitar..."></textarea>
                            <div class="invalid-feedback" id="err_kondisi_lingkungan"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tindakan yang Dilakukan <span class="text-danger">*</span></label>
                            <textarea name="tindakan_dilakukan" class="form-control" rows="3" minlength="5" required placeholder="Jelaskan tindakan yang telah dilakukan..."></textarea>
                            <div class="invalid-feedback" id="err_tindakan_dilakukan"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                            <i class="bi bi-save"></i> Simpan Pemeriksaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = 'api_proxy.php';

document.querySelector('input[name="tanggal_pemeriksaan"]').max = new Date().toISOString().split('T')[0];

function esc(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}

function showAlert(type, msg) {
    const c = document.getElementById('alertContainer');
    c.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show">${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
}

function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
}

function showFieldErrors(errors) {
    clearErrors();
    for (const [field, msgs] of Object.entries(errors)) {
        const input = document.querySelector(`[name="${field}"]`);
        const errEl = document.getElementById(`err_${field}`);
        if (input && errEl) {
            input.classList.add('is-invalid');
            errEl.textContent = msgs.join(', ');
        }
    }
}

async function loadTitikOptions() {
    try {
        const res = await fetch(API + '/titik-risiko');
        const json = await res.json();
        const select = document.getElementById('titikSelect');

        if (!json.success || !json.data) {
            select.innerHTML = '<option value="">Gagal memuat data</option>';
            return;
        }

        select.innerHTML = '<option value="">-- Pilih Titik Risiko --</option>';
        json.data.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.id;
            opt.textContent = t.nama_titik + ' (' + t.alamat + ')';
            select.appendChild(opt);
        });
    } catch (err) {
        document.getElementById('titikSelect').innerHTML = '<option value="">Gagal terhubung ke API</option>';
    }
}

document.getElementById('formPemeriksaan').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearErrors();

    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

    const formData = new FormData(this);
    const data = {};
    formData.forEach((val, key) => { data[key] = val; });

    try {
        const res = await fetch(API + '/pemeriksaan-risiko', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data),
        });
        const json = await res.json();

        if (json.success) {
            showAlert('success', '<i class="bi bi-check-circle"></i> Data pemeriksaan berhasil disimpan!');
            this.reset();
            document.querySelector('input[name="tanggal_pemeriksaan"]').max = new Date().toISOString().split('T')[0];
        } else {
            if (json.errors) {
                showFieldErrors(json.errors);
                showAlert('danger', '<i class="bi bi-exclamation-triangle"></i> ' + (json.message || 'Validasi gagal.'));
            } else {
                showAlert('danger', '<i class="bi bi-exclamation-triangle"></i> ' + (json.message || 'Gagal menyimpan.'));
            }
        }
    } catch (err) {
        showAlert('danger', '<i class="bi bi-exclamation-triangle"></i> Gagal terhubung ke server API. Pastikan server Laravel berjalan.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save"></i> Simpan Pemeriksaan';
    }
});

loadTitikOptions();
</script>
</body>
</html>
