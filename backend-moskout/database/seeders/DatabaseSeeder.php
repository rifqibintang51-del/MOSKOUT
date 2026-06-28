<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TitikRisiko;
use App\Models\PemeriksaanRisiko;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Kosongkan database sebelum seeding agar tidak terjadi duplikasi/error unique constraint
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        User::truncate();
        TitikRisiko::truncate();
        PemeriksaanRisiko::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Seed Akun dengan role yang benar
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@moskout.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas Lapangan',
            'email' => 'petugas@moskout.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // 2. Seed Titik Risiko (Locations in Malang)
        $titik1 = TitikRisiko::create([
            'nama_titik' => 'Perumahan Griya Shanta',
            'alamat' => 'Jl. MT Haryono No.168, Lowokwaru, Malang',
            'rt_rw' => '005/003',
            'latitude' => -7.944513,
            'longitude' => 112.620947,
            'jenis_risiko' => 'genangan',
            'level_risiko_awal' => 'tinggi',
            'status_aktif' => true,
        ]);

        $titik2 = TitikRisiko::create([
            'nama_titik' => 'Gang Sawojajar V',
            'alamat' => 'Sawojajar, Kec. Kedungkandang, Malang',
            'rt_rw' => '002/007',
            'latitude' => -7.987654,
            'longitude' => 112.654321,
            'jenis_risiko' => 'barang bekas',
            'level_risiko_awal' => 'sedang',
            'status_aktif' => true,
        ]);

        $titik3 = TitikRisiko::create([
            'nama_titik' => 'Lingkungan Dinoyo Permai',
            'alamat' => 'Jl. Dinoyo, Kec. Lowokwaru, Malang',
            'rt_rw' => '003/002',
            'latitude' => -7.938276,
            'longitude' => 112.618975,
            'jenis_risiko' => 'saluran air',
            'level_risiko_awal' => 'rendah',
            'status_aktif' => true,
        ]);

        $titik4 = TitikRisiko::create([
            'nama_titik' => 'TPS Sukun',
            'alamat' => 'Jl. Sukun Raya, Kec. Sukun, Malang',
            'rt_rw' => '001/004',
            'latitude' => -7.978912,
            'longitude' => 112.635871,
            'jenis_risiko' => 'tempat sampah',
            'level_risiko_awal' => 'tinggi',
            'status_aktif' => true,
        ]);

        $titik5 = TitikRisiko::create([
            'nama_titik' => 'Perumahan Buring Permai',
            'alamat' => 'Buring, Kec. Kedungkandang, Malang',
            'rt_rw' => '007/003',
            'latitude' => -8.001234,
            'longitude' => 112.681234,
            'jenis_risiko' => 'genangan',
            'level_risiko_awal' => 'sedang',
            'status_aktif' => true,
        ]);

        $titik6 = TitikRisiko::create([
            'nama_titik' => 'Kelurahan Tlogomas',
            'alamat' => 'Jl. Tlogomas, Kec. Lowokwaru, Malang',
            'rt_rw' => '001/001',
            'latitude' => -7.929876,
            'longitude' => 112.609876,
            'jenis_risiko' => 'saluran air',
            'level_risiko_awal' => 'tinggi',
            'status_aktif' => true,
        ]);

        // 3. Seed Pemeriksaan Risiko
        PemeriksaanRisiko::create([
            'titik_risiko_id' => $titik1->id,
            'petugas_id' => 2,
            'tanggal_pemeriksaan' => '2026-05-20',
            'ditemukan_jentik' => 'ya',
            'kondisi_lingkungan' => 'Terdapat genangan air di belakang rumah warga, banyak sampah plastik.',
            'tindakan_dilakukan' => 'Menguras bak mandi dan menutup tempat penampungan air.',
            'status_akhir' => 'perlu tindakan',
        ]);

        PemeriksaanRisiko::create([
            'titik_risiko_id' => $titik1->id,
            'petugas_id' => 2,
            'tanggal_pemeriksaan' => '2026-06-01',
            'ditemukan_jentik' => 'tidak',
            'kondisi_lingkungan' => 'Setelah tindakan, lingkungan lebih bersih. Tidak ada genangan air.',
            'tindakan_dilakukan' => 'Memberikan edukasi 3M Plus kepada warga sekitar.',
            'status_akhir' => 'aman',
        ]);

        PemeriksaanRisiko::create([
            'titik_risiko_id' => $titik2->id,
            'petugas_id' => 2,
            'tanggal_pemeriksaan' => '2026-05-25',
            'ditemukan_jentik' => 'tidak',
            'kondisi_lingkungan' => 'Barang bekas ditumpuk rapi, tidak ada genangan air hujan.',
            'tindakan_dilakukan' => 'Mengingatkan warga untuk mendaur ulang barang bekas.',
            'status_akhir' => 'perlu pemantauan',
        ]);

        PemeriksaanRisiko::create([
            'titik_risiko_id' => $titik4->id,
            'petugas_id' => 2,
            'tanggal_pemeriksaan' => '2026-06-05',
            'ditemukan_jentik' => 'ya',
            'kondisi_lingkungan' => 'TPS penuh, sampah berserakan, banyak wadah berisi air hujan.',
            'tindakan_dilakukan' => 'Koordinasi dengan dinas kebersihan untuk pengangkutan sampah.',
            'status_akhir' => 'perlu tindakan',
        ]);

        PemeriksaanRisiko::create([
            'titik_risiko_id' => $titik6->id,
            'petugas_id' => 2,
            'tanggal_pemeriksaan' => '2026-06-08',
            'ditemukan_jentik' => 'ya',
            'kondisi_lingkungan' => 'Saluran air tersumbat sampah, air menggenang dan berbau.',
            'tindakan_dilakukan' => 'Membersihkan saluran air dan mengedukasi warga.',
            'status_akhir' => 'perlu tindakan',
        ]);
    }
}
