# 📘 Panduan Setup & Implementasi Project WaspadaDBD

Project **WaspadaDBD** adalah sistem pemantauan risiko demam berdarah dengue (DBD) berbasis lingkungan yang menggunakan arsitektur **decoupled** (Frontend & Backend terpisah).

- **Backend (Laravel):** Menyediakan REST API, Web Dashboard, dan modul CRUD Admin/Petugas terproteksi oleh Middleware Auth.
- **Frontend (PHP Native):** Sisi publik yang menampilkan peta sebaran dan riwayat pemeriksaan dengan menarik menggunakan data dari REST API Laravel via cURL.

---

## 1. Langkah-langkah Setup Project

Ikuti langkah-langkah di bawah ini untuk menjalankan project secara lokal di komputer Anda:

### Langkah A: Konfigurasi Database & Environment
1. Buka file `.env` di root project Laravel Anda.
2. Atur nama database Anda (misalnya menggunakan MySQL):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=UAS_PBO
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Pastikan web server database lokal Anda (seperti XAMPP / Laragon) sudah berjalan dan buatlah database kosong bernama `UAS_PBO`.

### Langkah B: Jalankan Migration & Database Seeding
Jalankan perintah berikut pada terminal di root folder project:
```bash
# 1. Jalankan migrasi tabel
php artisan migrate --force

# 2. Isi database dengan data awal dan akun demo admin
php artisan db:seed
```

### Langkah C: Jalankan Server Lokal
Jalankan server backend Laravel menggunakan PHP Artisan:
```bash
php artisan serve
```
> [!IMPORTANT]
> Secara default, Laravel akan berjalan di **`http://127.0.0.1:8000`**. Jika port Anda berbeda, silakan ubah konstanta `BASE_API` pada file [config.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/public/frontend/config.php).

---

## 2. Struktur Kode Backend (Laravel)

### A. File Migrasi Database
Tabel didefinisikan menggunakan migration dengan relasi **One-to-Many** di mana satu `TitikRisiko` memiliki banyak `PemeriksaanRisiko`:

1. **Titik Risiko:** [2026_06_12_085634_create_titik_risikos_table.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/database/migrations/2026_06_12_085634_create_titik_risikos_table.php)
2. **Pemeriksaan Risiko:** [2026_06_12_085635_create_pemeriksaan_risikos_table.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/database/migrations/2026_06_12_085635_create_pemeriksaan_risikos_table.php)

### B. File Eloquent Model
1. **TitikRisiko:** [TitikRisiko.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/app/Models/TitikRisiko.php)
2. **PemeriksaanRisiko:** [PemeriksaanRisiko.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/app/Models/PemeriksaanRisiko.php)

### C. File Controller Dashboard & CRUD (Web Panel)
Kami telah membangun controller terpisah untuk mematuhi Single Responsibility Principle:
1. **AuthController:** [AuthController.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/app/Http/Controllers/AuthController.php) (Untuk login/logout admin).
2. **DashboardController:** [DashboardController.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/app/Http/Controllers/DashboardController.php) (Statistik dashboard).
3. **TitikRisikoController:** [TitikRisikoController.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/app/Http/Controllers/TitikRisikoController.php) (CRUD lokasi pantau).
4. **PemeriksaanRisikoController:** [PemeriksaanRisikoController.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/app/Http/Controllers/PemeriksaanRisikoController.php) (CRUD survei jentik).

### D. REST API Controller
- **ApiWaspadaController:** [ApiWaspadaController.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/app/Http/Controllers/ApiWaspadaController.php)
  - `GET /api/titik-risiko`
  - `GET /api/titik-risiko/{id}/pemeriksaan`
  - `POST /api/pemeriksaan-risiko`

### E. Akun Admin Demo untuk Login
Setelah melakukan database seeding (`db:seed`), Anda dapat login menggunakan akun berikut:
- **Email:** `admin@waspadadbd.test`
- **Password:** `admin123`

---

## 3. Struktur Kode Frontend (PHP Native)

Seluruh file frontend diletakkan pada folder `/public/frontend/` agar dapat diakses secara langsung:

### 1. File Konfigurasi cURL: [config.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/public/frontend/config.php)
Menggunakan cURL dengan error-handling dan timeout terintegrasi untuk komunikasi aman dengan API Laravel:
```php
function fetch_api($endpoint, $method = 'GET', $data = null) {
    $url = BASE_API . $endpoint;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    // ...
    $response = curl_exec($ch);
    // ...
    return json_decode($response, true);
}
```

### 2. Beranda Publik: [index.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/public/frontend/index.php)
- Menampilkan data lokasi pantau dalam bentuk grid Card Bootstrap yang interaktif.
- Dilengkapi dengan status warna otomatis (Risiko Tinggi = Merah, Sedang = Kuning, Rendah = Hijau).

### 3. Detail Riwayat Lokasi: [detail.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/public/frontend/detail.php)
- Menampilkan detail info geografis (latitude, longitude) serta tabel riwayat survei lapangan oleh petugas.
- Terdapat tombol **Google Maps** dinamis yang terhubung ke lokasi koordinat.

### 4. Portal Edukasi 3M Plus: [edukasi.php](file:///d:/Kuliah/Semester%204/Pemrograman%20Web/TUBES/UAS2026PBL/public/frontend/edukasi.php)
- Halaman informasi statis tentang Gerakan 3M (Menguras, Menutup, Mendaur ulang) serta informasi ciri-ciri nyamuk demam berdarah.

---

## 4. Cara Pengujian Aplikasi

1. **Akses Frontend Publik:**
   Buka browser dan buka alamat:
   ```text
   http://127.0.0.1:8000/frontend/index.php
   ```
   *Anda akan melihat dashboard publik dengan kartu-kartu lokasi risiko yang dinamis dari database.*

2. **Akses Dashboard Admin:**
   Klik tombol **Login Petugas** di sudut kanan atas menu navigasi frontend, atau buka:
   ```text
   http://127.0.0.1:8000/login
   ```
   Masukkan kredensial `admin@waspadadbd.test` dan password `admin123`.

3. **Kelola Data (CRUD):**
   Melalui panel admin, coba lakukan:
   - Tambah/Ubah/Hapus lokasi titik risiko baru.
   - Input laporan hasil survei pemeriksaan lapangan terbaru.
   - Lihat perubahan data secara realtime di portal publik frontend dengan me-refresh halaman `index.php`.
