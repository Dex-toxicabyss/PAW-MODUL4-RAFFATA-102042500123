# CeLOE LMS — Sistem Manajemen Pembelajaran (Jurnal 4 Praktikum PAW)

Aplikasi web Learning Management System (LMS) berbasis **Laravel 12** yang memperluas fungsionalitas manajemen kelas perkuliahan dengan proteksi rute berbasis peran (**Role-Based Access Control** untuk `dosen` dan `mahasiswa`), penyaringan serta pengurutan katalog mata kuliah, fitur penilaian tugas (*grading*) beserta catatan dosen, dan ekspor rekap nilai ke format **CSV**.

Repositori ini merupakan hasil penyelesaian **Jurnal 4 Praktikum Pengembangan Aplikasi Website (PAW)** berdasarkan starter code [kal-ngga/JURNAL4_PRAKTIKUM-PAW](https://github.com/kal-ngga/JURNAL4_PRAKTIKUM-PAW).

## Identitas Pengembang

| Atribut | Detail | 
| ----- | ----- | 
| **Nama Lengkap** | Raffata Izacky Yuargya Aletama | 
| **NIM** | 102042500123 | 
| **Kelas** | S1-SI-KJ-25-02 | 
| **Kode Asisten** | MARK | 
| **Mata Kuliah** | Pengembangan Aplikasi Website (Jurnal 4 — Laravel Lanjutan) | 
| **Program Studi** | S1 Sistem Informasi — Fakultas Rekayasa Industri, Telkom University Jakarta | 

## Fitur Utama (TODO 1 – TODO 8)

1. **Proteksi Rute Multi-Role (`RoleMiddleware`)**

   * Mengelompokkan seluruh rute terproteksi di dalam `middleware('auth')` serta memisahkan hak akses khusus `role:dosen` dan `role:mahasiswa`.

   * Mengembalikan respons `403 Forbidden` apabila pengguna mengakses rute di luar kewenangan perannya.

2. **Filter & Pengurutan Katalog Mata Kuliah (`MataKuliahController`)**

   * Filter daftar mata kuliah berdasarkan parameter `semester`.

   * Pengurutan dinamis berdasarkan nama (`nama_asc`, `nama_desc`) dan bobot SKS (`sks_desc`, `sks_asc`) sambil mempertahankan query string pada URL.

3. **Penilaian Tugas & Accessor Status Nilai (`Submission` & `SubmissionController@grade`)**

   * Penambahan kolom `nilai` (`unsignedTinyInteger`, rentang `0–100`) dan `catatan_dosen` (`text`, maks. 1000 karakter) pada tabel `submissions`.

   * Accessor `status_nilai` (`getStatusNilaiAttribute()`) yang mengelompokkan status secara otomatis menjadi `Belum Dinilai`, `Lulus` (`>= 70`), atau `Perlu Perbaikan` (`< 70`).

   * Panel ringkasan statistik pengumpulan tugas (Total Mahasiswa, Sudah Mengumpulkan, dan Rata-rata Nilai) serta formulir penilaian `PATCH` per mahasiswa.

4. **Ekspor Rekap Nilai ke CSV (`SubmissionController@exportCsv`)**

   * Mengunduh rekap nilai pengumpulan tugas secara *streaming* menggunakan `response()->streamDownload()` dan `fputcsv()` tanpa membebani memori server.

## Tech Stack

* **Backend**: PHP >= 8.2, Laravel 12

* **Database**: MySQL / MariaDB

* **Frontend**: Laravel Blade, Bootstrap / CSS, Vite

* **Version Control**: Git & GitHub (Conventional Commits)

## Panduan Instalasi Cepat (Quick Start)

1. **Clone repositori dan masuk ke direktori proyek:**

   ```
   git clone https://github.com/Dex-toxicabyss/PAW-JURNAL4-RAFFATA-102042500123.git
   cd PAW-JURNAL4-RAFFATA-102042500123
   
   ```

2. **Instal dependensi PHP dan salin konfigurasi environment:**

   ```
   composer install
   cp .env.example .env
   php artisan key:generate
   
   ```

3. **Konfigurasi koneksi database pada `.env`:**

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_celoe_lms
   DB_USERNAME=root
   DB_PASSWORD=
   
   ```

   *(Sesuaikan `DB_PORT` dan `DB_PASSWORD` apabila menggunakan Docker MySQL, misalnya port `3307` dan password `root`).*

4. **Jalankan migrasi dan seeder data awal:**

   ```
   php artisan migrate:fresh --seed
   
   ```

5. **Jalankan server pengembangan lokal:**

   ```
   php artisan serve
   
   ```

   Buka `http://127.0.0.1:8000` di peramban web.

## Akun Pengujian Default (`DatabaseSeeder`)

| Peran (Role) | Email | Password | Nomor Induk (NIP/NIM) | 
| ----- | ----- | ----- | ----- | 
| **Dosen** | `dosen@celoe.test` | `dosen123` | `198501012015041001` | 
| **Mahasiswa** | `mahasiswa@celoe.test` | `mahasiswa123` | `1301210001` | 

## Daftar Berkas yang Dikerjakan (TODO 1 – TODO 8)

| TODO | Path Berkas | Ringkasan Implementasi | 
| ----- | ----- | ----- | 
| **1** | `database/migrations/2026_10_25_000001_add_grading_to_submissions_table.php` | Menambahkan kolom `nilai` (`unsignedTinyInteger`, `nullable`) dan `catatan_dosen` (`text`, `nullable`) setelah `jawaban`. | 
| **2** | `app/Models/Submission.php` | Menambahkan `'nilai'` dan `'catatan_dosen'` pada `$fillable` serta accessor `getStatusNilaiAttribute()`. | 
| **3** | `routes/web.php` | Membungkus rute terproteksi dengan `auth`, memisahkan grup `role:dosen` dan `role:mahasiswa`, serta mengaktifkan rute `submissions.grade` & `assignments.export-csv`. | 
| **4** | `app/Http/Controllers/MataKuliahController.php` | Implementasi filter `semester` dan sorting (`nama_asc`, `nama_desc`, `sks_desc`, `sks_asc`) pada `index()`. | 
| **5a & 5b** | `app/Http/Controllers/SubmissionController.php` | Implementasi method `grade()` (otorisasi dosen, validasi `0–100`) dan `exportCsv()` (`streamDownload` + `fputcsv`). | 
| **6** | `resources/views/courses/index.blade.php` | Penambahan dropdown filter `<select name="semester">` dan pengurutan `<select name="sort">`. | 
| **7a, 7b, 7c** | `resources/views/assignments/submissions.blade.php` | Kartu statistik nilai, tombol Export CSV, dan form `PATCH` penilaian dosen per pengumpulan. | 
| **8** | `database/seeders/DatabaseSeeder.php` | Penambahan minimal 2 mata kuliah (semester & SKS berbeda), 1 akun mahasiswa tambahan, minimal 2 tugas, dan sampel pengumpulan. | 

## Pengujian & Verifikasi

```
php artisan route:list
php artisan test

```