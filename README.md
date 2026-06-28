# MOSKOUT — Waspada DBD

Sistem monitoring dan pengendalian titik risiko Demam Berdarah Dengue (DBD) berbasis web. Aplikasi ini memungkinkan admin mengelola titik-titik risiko DBD dan petugas lapangan melakukan pemeriksaan secara berkala, dilengkapi dengan sistem revisi untuk menjaga riwayat pemeriksaan tetap akurat.

---

## Fitur Utama

- Manajemen titik risiko DBD (CRUD) dengan peta interaktif
- Pemeriksaan lapangan oleh petugas dengan revision tracking
- Dashboard admin & petugas dengan statistik real-time
- Rekap & laporan dengan filter (tanggal, status, petugas, level risiko)
- Grafik interaktif (Chart.js) — batang per bulan & pie distribusi status
- Cascading dropdown wilayah administratif (provinsi → kabupaten → kecamatan → kelurahan)
- Public portal konsumen (PHP native) yang mengonsumsi JSON API
- Autentikasi dua role: Admin dan Petugas

---

## Anggota Kelompok

| Nama | NIM | Peran |
|------|-----|-------|
| Devia Amanda Prameswari | 24102007 | UI/UX Design & Laporan |
| Risky Putra Adielia | 24102020 | Frontend & Backend |
| Rifqi Bintang Syahputra | 24102017 | Frontend & Backend |
| Eka Yuvita Sari Widya Diningrum | 24102008 | System Analyst |

---

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend Framework | Laravel 13 |
| Database | MySQL |
| Authentication | Laravel Sanctum (API) + Session (Web) |
| Frontend (Admin/Petugas) | Blade + Bootstrap 5 + Bootstrap Icons |
| Frontend (Public Portal) | PHP Native + JavaScript Fetch |
| Peta Interaktif | Leaflet + OpenStreetMap + Nominatim |
| Grafik | Chart.js |
| Asset Bundler | Vite + Tailwind CSS (konfigurasi dasar) |

---

## Arsitektur Aplikasi

Aplikasi menggunakan arsitektur **decoupled**:

1. **Backend Laravel** — Melayani dua antarmuka sekaligus:
   - **Blade Views** — Dashboard admin dan petugas (server-side rendering)
   - **JSON REST API** — Dikonsumsi oleh public portal frontend (PHP native)
2. **Public Portal** — Berada di `public/frontend/`, merupakan halaman PHP native yang mengambil data dari `localhost:8000/api` menggunakan JavaScript `fetch()` dan cURL.

---

## Role & Autentikasi

### Dua Role Pengguna

| Role | Akses |
|------|-------|
| **Admin** | Dashboard, CRUD titik risiko, rekap & laporan, proxy wilayah |
| **Petugas** | Dashboard, CRUD pemeriksaan risiko (dengan revision tracking) |

### Alur Autentikasi

1. Login via `/login` — validasi email & password
2. `AuthController@login` mengautentikasi dan mengarahkan berdasarkan role
3. `RoleMiddleware` memeriksa role pada setiap request ke route yang dibatasi
4. Logout via `POST /logout` — session di-invalidate dan CSRF token di-regenerate

### Akun Default (Seeder)

| Email | Password | Role |
|-------|----------|------|
| `admin@moskout.com` | `password` | admin |
| `petugas@moskout.com` | `password` | petugas |

---

## Database Schema

### `users`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint AI PK | |
| name | varchar(255) | |
| email | varchar(255) | UNIQUE |
| password | varchar(255) | hashed |
| role | enum('admin','petugas') | default 'petugas' |
| remember_token | varchar(100) | nullable |
| timestamps | timestamp | created_at, updated_at |

### `titik_risikos`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint AI PK | |
| nama_titik | varchar(150) | Nama lokasi |
| alamat | text | Alamat lengkap |
| rt_rw | varchar(20) | nullable |
| provinsi | varchar(100) | nullable |
| kabupaten | varchar(100) | nullable |
| kecamatan | varchar(100) | nullable |
| kelurahan | varchar(100) | nullable |
| latitude | decimal(10,7) | Koordinat |
| longitude | decimal(10,7) | Koordinat |
| jenis_risiko | enum('genangan','barang bekas','saluran air','tempat sampah') | |
| level_risiko_awal | enum('rendah','sedang','tinggi') | |
| status_aktif | boolean | default true |
| timestamps | timestamp | |

### `pemeriksaan_risikos`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint AI PK | |
| titik_risiko_id | bigint FK | ON DELETE CASCADE |
| petugas_id | integer | references users(id) |
| tanggal_pemeriksaan | date | |
| ditemukan_jentik | enum('ya','tidak') | |
| kondisi_lingkungan | text | |
| tindakan_dilakukan | text | |
| status_akhir | enum('aman','perlu pemantauan','perlu tindakan') | |
| revisi_ke | integer | default 1 |
| timestamps | timestamp | |

---

## API Endpoints

### JSON API (REST) — `routes/api.php`

Endpoint publik dan terautentikasi (Sanctum) untuk konsumen eksternal / mobile.

| Method | URL | Auth | Deskripsi |
|--------|-----|------|-----------|
| `GET` | `/api/titik-risiko` | Public | Semua titik risiko aktif dengan riwayat pemeriksaan terakhir |
| `GET` | `/api/titik-risiko/level/{level}` | Public | Filter titik risiko berdasarkan level risiko (`rendah`, `sedang`, `tinggi`) |
| `GET` | `/api/titik-risiko/{id}/pemeriksaan` | Public | Riwayat pemeriksaan detail untuk satu titik risiko |
| `POST` | `/api/pemeriksaan-risiko` | Sanctum `auth` | Simpan pemeriksaan baru |

**Request Body — `POST /api/pemeriksaan-risiko`:**

| Field | Tipe | Validasi |
|-------|------|----------|
| `titik_risiko_id` | integer | required |
| `petugas_id` | integer | required |
| `tanggal_pemeriksaan` | date | required |
| `ditemukan_jentik` | string | required, `ya` / `tidak` |
| `kondisi_lingkungan` | string | required, min 10 karakter |
| `tindakan_dilakukan` | string | required, min 5 karakter |
| `status_akhir` | string | required, `aman` / `perlu pemantauan` / `perlu tindakan` |

---

### Web Routes (HTML)

#### Authentication

| Method | URL | Deskripsi |
|--------|-----|-----------|
| `GET` | `/login` | Tampilkan form login |
| `POST` | `/login` | Proses login |
| `POST` | `/logout` | Logout |

#### Admin — `middleware: auth + role:admin`

| Method | URL | Deskripsi |
|--------|-----|-----------|
| `GET` | `/admin/dashboard` | Dashboard admin (total titik risiko, level tinggi, 5 terbaru) |
| `GET` | `/admin/rekap` | Rekap & laporan (filter tanggal, status, petugas, level) |
| `GET` | `/admin/titik-risiko` | Daftar semua titik risiko |
| `GET` | `/admin/titik-risiko/create` | Form tambah titik risiko |
| `POST` | `/admin/titik-risiko` | Simpan titik risiko baru |
| `GET` | `/admin/titik-risiko/{id}/edit` | Form edit titik risiko |
| `PUT` | `/admin/titik-risiko/{id}` | Update titik risiko |
| `DELETE` | `/admin/titik-risiko/{id}` | Hapus titik risiko |
| `GET` | `/admin/wilayah/provinces` | Data provinsi (proxy ke API wilayah) |
| `GET` | `/admin/wilayah/regencies/{province_id}` | Data kabupaten/kota |
| `GET` | `/admin/wilayah/districts/{regency_id}` | Data kecamatan |
| `GET` | `/admin/wilayah/villages/{district_id}` | Data kelurahan/desa |

#### Petugas — `middleware: auth + role:petugas`

| Method | URL | Deskripsi |
|--------|-----|-----------|
| `GET` | `/petugas/dashboard` | Dashboard petugas (total pemeriksaan, bulan ini, jentik) |
| `GET` | `/petugas/pemeriksaan-risiko` | Daftar semua pemeriksaan |
| `GET` | `/petugas/pemeriksaan-risiko/create` | Form tambah pemeriksaan |
| `POST` | `/petugas/pemeriksaan-risiko` | Simpan pemeriksaan baru |
| `GET` | `/petugas/pemeriksaan-risiko/{id}/edit` | Form edit pemeriksaan |
| `PUT` | `/petugas/pemeriksaan-risiko/{id}` | Update pemeriksaan (buat revisi baru) |
| `DELETE` | `/petugas/pemeriksaan-risiko/{id}` | Hapus pemeriksaan |

---

## Fitur Kunci

### Revision Tracking Pemeriksaan

Sistem menggunakan pola **immutable revision history**:

1. **Pembuatan awal** — `revisi_ke` diset ke `1`
2. **Edit** — Tidak mengubah record lama, melainkan membuat **record baru** dengan `revisi_ke` di-increment
3. Riwayat pemeriksaan lama tetap tersimpan dan tidak berubah

### Cascading Wilayah Dropdown

Data wilayah (provinsi → kabupaten → kecamatan → kelurahan) diambil dari [emsifa/api-wilayah-indonesia](https://github.com/emsifa/api-wilayah-indonesia) melalui **proxy server-side** di `TitikRisikoController@wilayah` untuk menghindari masalah CORS.

### Peta Interaktif (Leaflet)

- Titik risiko ditampilkan pada peta OpenStreetMap
- Reverse geocoding otomatis menggunakan Nominatim API saat admin mengklik peta
- Pusat peta di area Malang

### Rekap & Grafik (Chart.js)

- **Bar chart** — Distribusi pemeriksaan per bulan
- **Doughnut chart** — Pie distribusi status akhir (aman / perlu pemantauan / perlu tindakan)
- Filter berdasarkan rentang tanggal, status, petugas, dan level risiko

---

## Cara Install & Menjalankan

### Prasyarat

- PHP ^8.3
- Composer
- MySQL
- Node.js & npm (untuk Vite)

### Langkah

```bash
# 1. Clone repository
git clone <repo-url>
cd uaspemweb

# 2. Install PHP dependencies
composer install

# 3. Copy environment
copy .env.example .env
# atau: cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Setup database
# Buat database MySQL bernama 'moskout', lalu:
php artisan migrate --seed

# 6. Install Node dependencies (opsional, untuk Vite)
npm install

# 7. Jalankan aplikasi
php artisan serve
# Aplikasi akan berjalan di http://localhost:8000
```

### Menjalankan Public Portal

Public portal berada di `public/frontend/`. Akses melalui browser saat `php artisan serve` berjalan, misal:
- `http://localhost:8000/frontend/index.php`
- `http://localhost:8000/frontend/daftar-risiko.php`
- `http://localhost:8000/frontend/detail.php?id=1`
- `http://localhost:8000/frontend/tambah-pemeriksaan.php`
- `http://localhost:8000/frontend/edukasi.php`

### Akun Default

| Email | Password | Role |
|-------|----------|------|
| `admin@moskout.com` | `password` | admin |
| `petugas@moskout.com` | `password` | petugas |

---

## Struktur Direktori

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PetugasDashboardController.php
│   │   │   ├── TitikRisikoController.php
│   │   │   ├── PemeriksaanRisikoController.php
│   │   │   ├── RekapController.php
│   │   │   └── ApiWaspadaController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── TitikRisiko.php
│       └── PemeriksaanRisiko.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2025_01_20_142124_add_role_to_users_table.php
│   │   ├── 2025_01_20_142510_create_titik_risikos_table.php
│   │   ├── 2025_01_20_142635_create_pemeriksaan_risikos_table.php
│   │   ├── 2025_06_14_155953_add_wilayah_to_titik_risikos_table.php
│   │   └── 2025_06_14_160041_add_revisi_ke_to_pemeriksaan_risikos_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   └── frontend/
│       ├── config.php
│       ├── index.php
│       ├── daftar-risiko.php
│       ├── detail.php
│       ├── detail-pemeriksaan.php
│       ├── edukasi.php
│       └── tambah-pemeriksaan.php
├── resources/views/
│   ├── auth/login.blade.php
│   ├── layouts/admin.blade.php
│   ├── layouts/petugas.blade.php
│   ├── admin/dashboard.blade.php
│   ├── admin/rekap/index.blade.php
│   ├── admin/titik-risiko/index.blade.php
│   ├── admin/titik-risiko/create.blade.php
│   ├── admin/titik-risiko/edit.blade.php
│   ├── petugas/dashboard.blade.php
│   ├── petugas/pemeriksaan-risiko/index.blade.php
│   ├── petugas/pemeriksaan-risiko/create.blade.php
│   └── petugas/pemeriksaan-risiko/edit.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── curl_test.php
├── final_test.php
└── setup_and_guide.md
```
