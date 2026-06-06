# Sistem Pendukung Keputusan Pemilihan Duta PNJ

Sistem Pendukung Keputusan Pemilihan Duta PNJ adalah aplikasi web berbasis Laravel untuk mengelola proses seleksi Duta Kampus PNJ, mulai dari pendaftaran peserta, validasi berkas, manajemen kriteria, pengelolaan juri, penjadwalan wawancara, penilaian peserta, perhitungan metode ARAS, hingga publikasi pengumuman hasil seleksi.

Aplikasi ini dikembangkan untuk kebutuhan proyek mata kuliah Framework dan Sistem Pendukung Keputusan.

---

## Daftar Isi

* [Fitur Utama](#fitur-utama)
* [Role Pengguna](#role-pengguna)
* [Teknologi yang Digunakan](#teknologi-yang-digunakan)
* [Metode SPK ARAS](#metode-spk-aras)
* [Alur Sistem](#alur-sistem)
* [Struktur Modul](#struktur-modul)
* [Persyaratan Sistem](#persyaratan-sistem)
* [Instalasi Project](#instalasi-project)
* [Konfigurasi Environment](#konfigurasi-environment)
* [Menjalankan Aplikasi](#menjalankan-aplikasi)
* [Akun Dummy](#akun-dummy)
* [Route Halaman](#route-halaman)
* [Endpoint API Utama](#endpoint-api-utama)
* [Struktur Folder Penting](#struktur-folder-penting)
* [Testing Manual](#testing-manual)
* [Troubleshooting](#troubleshooting)
* [Catatan Pengembangan](#catatan-pengembangan)

---

## Fitur Utama

### Public

* Landing page informasi pemilihan Duta PNJ.
* Form pendaftaran peserta.
* Halaman sukses pendaftaran.
* Halaman pengumuman publik.
* Pengumuman hanya tampil jika admin sudah mempublikasikan hasil seleksi.

### Admin

* Dashboard admin.
* Manajemen data pendaftar.
* Validasi dan penolakan peserta.
* Manajemen kriteria penilaian.
* Sinkronisasi kriteria.
* Manajemen akun juri.
* Reset password juri.
* Aktivasi dan nonaktivasi akun juri.
* Assign kriteria ke juri.
* Generate jadwal wawancara otomatis.
* Reset jadwal wawancara.
* Detail dan edit jadwal wawancara.
* Monitoring kelengkapan penilaian.
* Perhitungan ARAS.
* Publikasi dan pembatalan publikasi pengumuman.

### Juri

* Dashboard juri.
* Melihat kriteria yang ditugaskan.
* Melihat daftar peserta yang perlu dinilai.
* Melihat detail peserta.
* Mengisi nilai peserta.
* Melihat riwayat penilaian.
* Melihat detail riwayat nilai.
* Nilai dikunci jika hasil seleksi sudah dipublikasikan.

---

## Role Pengguna

### Admin

Admin bertanggung jawab mengelola proses seleksi dari awal sampai akhir.

Hak akses admin meliputi:

* Mengelola periode seleksi.
* Mengelola data pendaftar.
* Mengelola kriteria.
* Mengelola akun juri.
* Assign kriteria ke juri.
* Mengatur jadwal wawancara.
* Memantau kelengkapan nilai.
* Menghitung hasil ARAS.
* Mempublikasikan hasil seleksi.

### Juri

Juri bertanggung jawab memberikan nilai kepada peserta sesuai kriteria yang ditugaskan.

Hak akses juri meliputi:

* Melihat dashboard juri.
* Melihat daftar peserta.
* Melihat detail peserta.
* Mengisi nilai peserta.
* Melihat riwayat penilaian.

### Peserta / Publik

Peserta atau pengunjung umum dapat:

* Melihat landing page.
* Melakukan pendaftaran.
* Melihat pengumuman hasil seleksi jika sudah dipublikasikan.

---

## Teknologi yang Digunakan

Project ini menggunakan teknologi berikut:

* PHP 8.2 atau lebih baru
* Laravel 12
* Laravel Sanctum
* MySQL atau MariaDB
* Blade Template
* Tailwind CSS
* Vite
* JavaScript Fetch API
* Composer
* NPM

---

## Metode SPK ARAS

Sistem menggunakan metode ARAS atau Additive Ratio Assessment untuk menentukan ranking akhir peserta.

Alur perhitungan ARAS yang digunakan:

1. Mengambil nilai dari juri.
2. Merata-ratakan nilai juri untuk kandidat dan kriteria yang sama.
3. Membentuk matriks keputusan.
4. Menentukan alternatif ideal atau A0.
5. Melakukan normalisasi.
6. Mengalikan hasil normalisasi dengan bobot kriteria.
7. Menghitung nilai Si.
8. Menghitung nilai Ki.
9. Mengurutkan ranking berdasarkan nilai Ki terbesar.

Rumus umum:

```text
Ki = Si / Si A0
```

Keterangan:

* `Si` adalah total nilai terbobot peserta.
* `Si A0` adalah total nilai terbobot alternatif ideal.
* `Ki` adalah nilai utilitas akhir.
* Ranking terbaik adalah kandidat dengan `Ki` terbesar.

---

## Alur Sistem

### 1. Pendaftaran Peserta

Peserta mengisi form pendaftaran melalui halaman publik.

```text
Public Landing Page
→ Form Pendaftaran
→ Data masuk ke sistem
```

### 2. Validasi Admin

Admin memvalidasi data peserta.

```text
Admin
→ Data Pendaftar
→ Validasi / Tolak Peserta
```

### 3. Manajemen Kriteria

Admin mengatur kriteria penilaian, bobot, tipe, dan rentang nilai.

```text
Admin
→ Manajemen Kriteria
→ Simpan / Sinkronisasi Kriteria
```

### 4. Manajemen Juri

Admin membuat akun juri dan menugaskan kriteria kepada juri.

```text
Admin
→ Akun Juri
→ Assign Kriteria
```

### 5. Jadwal Wawancara

Admin membuat jadwal wawancara secara otomatis.

```text
Admin
→ Jadwal Wawancara
→ Generate Jadwal
```

Sistem akan membuat jadwal berdasarkan:

* periode seleksi
* tanggal wawancara
* jam mulai
* durasi per peserta
* lokasi wawancara
* urutan peserta

### 6. Penilaian Juri

Juri login dan memberi nilai peserta sesuai kriteria yang ditugaskan.

```text
Juri
→ Penilaian Peserta
→ Form Nilai
→ Simpan Nilai
```

### 7. Monitoring Penilaian

Admin melihat kelengkapan penilaian sebelum menghitung ARAS.

```text
Admin
→ Monitoring Penilaian
→ Cek kandidat lengkap / belum lengkap
```

### 8. Perhitungan ARAS

Admin menghitung hasil akhir menggunakan metode ARAS.

```text
Admin
→ Hasil ARAS
→ Hitung ARAS
→ Ranking terbentuk
```

### 9. Publikasi Pengumuman

Admin mempublikasikan hasil seleksi.

```text
Admin
→ Pengumuman
→ Cek Kesiapan
→ Publish
```

Jika masih ada kandidat yang belum dinilai lengkap, sistem menolak publikasi dan menampilkan warning.

### 10. Hasil Publik

Hasil seleksi tampil di halaman publik setelah dipublikasikan.

```text
Public
→ /pengumuman
→ Lihat hasil seleksi
```

---

## Struktur Modul

### Modul Public

| Modul        | Deskripsi                         |
| ------------ | --------------------------------- |
| Landing Page | Informasi umum pemilihan Duta PNJ |
| Pendaftaran  | Form pendaftaran peserta          |
| Pengumuman   | Halaman hasil seleksi publik      |

### Modul Admin

| Modul            | Deskripsi                                    |
| ---------------- | -------------------------------------------- |
| Dashboard        | Ringkasan data sistem                        |
| Data Pendaftar   | Kelola peserta yang mendaftar                |
| Kriteria         | Kelola kriteria dan bobot                    |
| Akun Juri        | Kelola user juri                             |
| Assign Kriteria  | Menentukan kriteria yang dinilai setiap juri |
| Jadwal Wawancara | Generate dan kelola jadwal wawancara         |
| Monitoring       | Melihat kelengkapan nilai                    |
| Hasil ARAS       | Menghitung dan melihat ranking akhir         |
| Pengumuman       | Publish atau unpublish hasil seleksi         |

### Modul Juri

| Modul             | Deskripsi                       |
| ----------------- | ------------------------------- |
| Dashboard         | Ringkasan tugas juri            |
| Penilaian Peserta | Input nilai peserta             |
| Riwayat Penilaian | Rekap nilai yang pernah diinput |

---

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

* PHP 8.2+
* Composer
* Node.js
* NPM
* MySQL atau MariaDB
* Web server lokal seperti Laragon, XAMPP, Laravel Herd, atau Laravel Valet

Untuk pengguna Laragon, project dapat dijalankan menggunakan domain lokal seperti:

```text
http://framework_spk.test
```

---

## Instalasi Project

Clone repository:

```bash
git clone https://github.com/sellolintang/framework_spk.git
cd framework_spk
```

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Copy file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

---

## Konfigurasi Environment

Buka file `.env`, lalu sesuaikan konfigurasi database.

Contoh konfigurasi MySQL:

```env
APP_NAME="Duta PNJ"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://framework_spk.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=framework_spk
DB_USERNAME=root
DB_PASSWORD=
```

Setelah konfigurasi selesai, jalankan migrasi:

```bash
php artisan migrate
```

Jika tersedia seeder, jalankan:

```bash
php artisan db:seed
```

Atau reset database dan seed ulang:

```bash
php artisan migrate:fresh --seed
```

---

## Menjalankan Aplikasi

Untuk development, jalankan Vite:

```bash
npm run dev
```

Jika menggunakan Laravel built-in server:

```bash
php artisan serve
```

Jika menggunakan Laragon, cukup aktifkan Laragon dan buka:

```text
http://framework_spk.test
```

Untuk build asset production:

```bash
npm run build
```

---

## Akun Dummy

Akun dapat menyesuaikan data seeder yang tersedia di project.

Contoh akun development:

### Admin

```text
Email    : admin@duta.test
Password : password
Role     : admin
```

### Juri

```text
Email    : juri1@duta.test
Password : password
Role     : juri
```

Catatan: akun dummy dapat berbeda tergantung isi seeder lokal.

---

## Route Halaman

### Public

| Method | URL                     | Deskripsi                  |
| ------ | ----------------------- | -------------------------- |
| GET    | `/`                     | Landing page               |
| GET    | `/registration`         | Form pendaftaran           |
| GET    | `/registration-success` | Halaman sukses pendaftaran |
| GET    | `/pengumuman`           | Pengumuman hasil seleksi   |
| GET    | `/login`                | Login                      |

### Admin

| Method | URL                                  | Deskripsi                 |
| ------ | ------------------------------------ | ------------------------- |
| GET    | `/admin/dashboard`                   | Dashboard admin           |
| GET    | `/admin/candidates`                  | Data pendaftar            |
| GET    | `/admin/criteria`                    | Manajemen kriteria        |
| GET    | `/admin/juries`                      | Daftar juri               |
| GET    | `/admin/juries/create`               | Tambah juri               |
| GET    | `/admin/juries/{jury}`               | Detail juri               |
| GET    | `/admin/juries/{jury}/edit`          | Edit juri                 |
| GET    | `/admin/juries/assign-criteria`      | Assign kriteria juri      |
| GET    | `/admin/interviews`                  | Jadwal wawancara          |
| GET    | `/admin/interviews/create`           | Generate jadwal wawancara |
| GET    | `/admin/interviews/{interview}`      | Detail jadwal wawancara   |
| GET    | `/admin/interviews/{interview}/edit` | Edit jadwal wawancara     |
| GET    | `/admin/monitoring`                  | Monitoring penilaian      |
| GET    | `/admin/aras`                        | Hasil ARAS                |
| GET    | `/admin/announcements`               | Publikasi pengumuman      |

### Juri

| Method | URL                              | Deskripsi                    |
| ------ | -------------------------------- | ---------------------------- |
| GET    | `/jury/dashboard`                | Dashboard juri               |
| GET    | `/jury/scoring`                  | Daftar peserta untuk dinilai |
| GET    | `/jury/scoring/{candidate}`      | Detail peserta               |
| GET    | `/jury/scoring/{candidate}/form` | Form penilaian               |
| GET    | `/jury/history`                  | Riwayat penilaian            |
| GET    | `/jury/history/{candidate}`      | Detail riwayat penilaian     |

---

## Endpoint API Utama

### Auth

| Method | Endpoint      | Deskripsi       |
| ------ | ------------- | --------------- |
| POST   | `/api/login`  | Login user      |
| POST   | `/api/logout` | Logout user     |
| GET    | `/api/me`     | Data user login |

### Candidate

| Method | Endpoint                               | Deskripsi          |
| ------ | -------------------------------------- | ------------------ |
| POST   | `/api/candidates/register`             | Registrasi peserta |
| GET    | `/api/candidates`                      | List peserta       |
| GET    | `/api/candidates/{candidate}`          | Detail peserta     |
| PATCH  | `/api/candidates/{candidate}/validate` | Validasi peserta   |
| PATCH  | `/api/candidates/{candidate}/reject`   | Tolak peserta      |

### Criteria

| Method    | Endpoint                    | Deskripsi             |
| --------- | --------------------------- | --------------------- |
| GET       | `/api/criteria`             | List kriteria         |
| POST      | `/api/criteria`             | Tambah kriteria       |
| PUT/PATCH | `/api/criteria/{criterion}` | Update kriteria       |
| DELETE    | `/api/criteria/{criterion}` | Hapus kriteria        |
| POST      | `/api/criteria/sync`        | Sinkronisasi kriteria |

### Juries

| Method    | Endpoint                            | Deskripsi           |
| --------- | ----------------------------------- | ------------------- |
| GET       | `/api/juries`                       | List juri           |
| GET       | `/api/juries/options`               | Opsi juri           |
| POST      | `/api/juries`                       | Tambah juri         |
| GET       | `/api/juries/{jury}`                | Detail juri         |
| PUT/PATCH | `/api/juries/{jury}`                | Update juri         |
| DELETE    | `/api/juries/{jury}`                | Hapus juri          |
| POST      | `/api/juries/{jury}/reset-password` | Reset password juri |
| PATCH     | `/api/juries/{jury}/toggle-status`  | Toggle status juri  |

### Jury Criteria

| Method | Endpoint                              | Deskripsi            |
| ------ | ------------------------------------- | -------------------- |
| GET    | `/api/jury-criteria`                  | List assign kriteria |
| GET    | `/api/jury-criteria/options`          | Opsi assign          |
| POST   | `/api/jury-criteria`                  | Tambah assign        |
| POST   | `/api/jury-criteria/sync`             | Sinkronisasi assign  |
| DELETE | `/api/jury-criteria/{jury_criterion}` | Hapus assign         |

### Interview

| Method | Endpoint                      | Deskripsi                |
| ------ | ----------------------------- | ------------------------ |
| GET    | `/api/interviews`             | List jadwal wawancara    |
| POST   | `/api/interviews/generate`    | Generate jadwal otomatis |
| POST   | `/api/interviews/reset`       | Reset jadwal wawancara   |
| GET    | `/api/interviews/{interview}` | Detail jadwal            |
| PATCH  | `/api/interviews/{interview}` | Update jadwal            |
| DELETE | `/api/interviews/{interview}` | Hapus jadwal             |

### Scores

| Method    | Endpoint              | Deskripsi              |
| --------- | --------------------- | ---------------------- |
| GET       | `/api/my-scores`      | Nilai milik juri login |
| GET       | `/api/scores`         | List nilai             |
| POST      | `/api/scores`         | Simpan nilai           |
| GET       | `/api/scores/{score}` | Detail nilai           |
| PUT/PATCH | `/api/scores/{score}` | Update nilai           |
| DELETE    | `/api/scores/{score}` | Hapus nilai            |

### Jury Portal API

| Method | Endpoint                                          | Deskripsi                    |
| ------ | ------------------------------------------------- | ---------------------------- |
| GET    | `/api/jury/dashboard-summary`                     | Summary dashboard juri       |
| GET    | `/api/jury/scoring-candidates`                    | Daftar peserta untuk dinilai |
| GET    | `/api/jury/scoring-candidates/{candidate}`        | Detail form nilai peserta    |
| POST   | `/api/jury/scoring-candidates/{candidate}/scores` | Simpan nilai peserta         |
| GET    | `/api/jury/scoring-history`                       | Riwayat penilaian juri       |
| GET    | `/api/jury/scoring-history/{candidate}`           | Detail riwayat penilaian     |

### Monitoring

| Method | Endpoint                 | Deskripsi                    |
| ------ | ------------------------ | ---------------------------- |
| GET    | `/api/monitoring/scores` | Monitoring kelengkapan nilai |

### ARAS

| Method | Endpoint                          | Deskripsi         |
| ------ | --------------------------------- | ----------------- |
| POST   | `/api/aras-results/calculate`     | Hitung ARAS       |
| GET    | `/api/aras-results`               | List hasil ARAS   |
| GET    | `/api/aras-results/{aras_result}` | Detail hasil ARAS |
| DELETE | `/api/aras-results/{aras_result}` | Hapus hasil ARAS  |

### Announcements

| Method | Endpoint                             | Deskripsi                  |
| ------ | ------------------------------------ | -------------------------- |
| POST   | `/api/announcements/check-readiness` | Cek kesiapan publikasi     |
| POST   | `/api/announcements/publish`         | Publish pengumuman         |
| POST   | `/api/announcements/unpublish`       | Batalkan publikasi         |
| GET    | `/api/public/results`                | Hasil seleksi untuk publik |

---

## Struktur Folder Penting

```text
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── AuthController.php
│           ├── CandidateController.php
│           ├── CriterionController.php
│           ├── JuryController.php
│           ├── JuryCriterionController.php
│           ├── InterviewController.php
│           ├── ScoreController.php
│           ├── ArasResultController.php
│           ├── AnnouncementController.php
│           ├── MonitoringController.php
│           ├── JuryDashboardController.php
│           └── JuryScoringController.php
├── Models/
│   ├── Candidate.php
│   ├── Criterion.php
│   ├── Interview.php
│   ├── Score.php
│   ├── ArasResult.php
│   └── ElectionPeriod.php

resources/
└── views/
    ├── public/
    ├── auth/
    ├── layouts/
    ├── partials/
    ├── admin/
    │   ├── candidates/
    │   ├── criteria/
    │   ├── juries/
    │   ├── interviews/
    │   ├── monitoring/
    │   ├── aras/
    │   └── announcements/
    └── jury/
        ├── dashboard.blade.php
        ├── scoring/
        └── history/

routes/
├── web.php
└── api.php

database/
├── migrations/
└── seeders/
```

---

## Testing Manual

### Admin Flow

1. Login sebagai admin.
2. Buka data pendaftar.
3. Validasi peserta.
4. Buka manajemen kriteria.
5. Pastikan bobot dan kriteria aktif sudah benar.
6. Buat akun juri.
7. Assign kriteria ke juri.
8. Generate jadwal wawancara.
9. Cek monitoring penilaian.
10. Hitung ARAS.
11. Cek hasil ranking.
12. Publish pengumuman.
13. Buka halaman `/pengumuman`.

### Juri Flow

1. Login sebagai juri.
2. Buka dashboard juri.
3. Buka menu Penilaian Peserta.
4. Pilih peserta.
5. Isi nilai berdasarkan kriteria yang ditugaskan.
6. Simpan nilai.
7. Buka Riwayat Penilaian.
8. Cek detail nilai peserta.

### Public Flow

1. Buka landing page.
2. Buka form pendaftaran.
3. Submit pendaftaran.
4. Buka halaman pengumuman.
5. Cek apakah hasil tampil setelah admin publish.

---

## Troubleshooting

### Vite manifest not found

Jika muncul error:

```text
Vite manifest not found at public/build/manifest.json
```

Jalankan:

```bash
npm run dev
```

Atau build asset:

```bash
npm run build
```

### Token login tidak ditemukan

Hapus localStorage browser, lalu login ulang.

Di browser console:

```js
localStorage.removeItem('duta_kampus_token');
localStorage.removeItem('duta_kampus_user');
```

### Route tidak berubah setelah edit

Jalankan:

```bash
php artisan optimize:clear
```

### Database ingin direset

Jalankan:

```bash
php artisan migrate:fresh --seed
```

### ARAS gagal dihitung karena nilai belum lengkap

Pastikan semua kandidat yang layak dihitung sudah memiliki nilai untuk seluruh kriteria aktif.

Cek melalui:

```text
/admin/monitoring
```

### Pengumuman tidak bisa dipublish

Sistem akan menolak publikasi jika:

* hasil ARAS belum dihitung
* masih ada kandidat yang belum dinilai lengkap
* jumlah hasil ARAS tidak sesuai dengan kandidat yang layak dipublikasikan

Cek melalui:

```text
/admin/announcements
```

---

## Catatan Pengembangan

* API menggunakan Laravel Sanctum.
* Halaman admin dan juri menggunakan Blade, Tailwind CSS, dan Fetch API.
* Perhitungan ARAS dilakukan di backend.
* Pengumuman publik hanya tampil jika admin sudah melakukan publish.
* Nilai juri dikunci setelah hasil seleksi dipublikasikan.
* Score tidak ditampilkan di halaman publik untuk menjaga hasil tetap sederhana dan mudah dipahami peserta.
* Sertifikat partisipasi belum disediakan sebagai fitur utama.

---

## Status Project

Project ini mencakup alur utama pemilihan Duta PNJ:

```text
Pendaftaran
→ Validasi Peserta
→ Manajemen Kriteria
→ Manajemen Juri
→ Assign Kriteria
→ Jadwal Wawancara
→ Penilaian Juri
→ Monitoring Penilaian
→ Perhitungan ARAS
→ Publikasi Pengumuman
```

---

## Lisensi

Project ini dibuat untuk kebutuhan pembelajaran dan tugas mata kuliah (Project Based Learning). Penggunaan, pengembangan, dan distribusi dapat disesuaikan dengan kebijakan tim pengembang dan institusi.
