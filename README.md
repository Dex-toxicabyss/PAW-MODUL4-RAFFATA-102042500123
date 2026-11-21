# Praktikum PAW — Modul 4 & Jurnal 4 (Laravel Lanjutan)

Repositori gabungan hasil pengerjaan **Modul 4** (*Sistem Peminjaman Buku Perpustakaan — TelkomLibrary*) dan **Jurnal 4** (*Sistem Manajemen Pembelajaran — CeLOE LMS*) pada mata kuliah **Pengembangan Aplikasi Website (PAW)** menggunakan **Laravel 12**.

## Identitas Praktikan

| Atribut | Detail | 
| ----- | ----- | 
| **Nama Lengkap** | Raffata Izacky Yuargya Aletama | 
| **NIM** | 102042500123 | 
| **Kelas** | S1-SI-KJ-25-02 | 
| **Kode Asisten** | MARK | 
| **Dosen Pembimbing** | Dewi Marini Umi Atmaja, S.Kom., M.Kom. | 
| **Program Studi** | S1 Sistem Informasi — Fakultas Rekayasa Industri, Telkom University Jakarta | 

## Struktur Repositori

```
PAW-MODUL4-RAFFATA-102042500123/
├── PAW_MODUL4_RAFFATA_102042500123/   # Proyek Modul 4: TelkomLibrary
├── PAW_JURNAL4_RAFFATA_102042500123/  # Proyek Jurnal 4: CeLOE LMS
├── screenshots/                       # Dokumentasi tampilan antarmuka aplikasi
│   ├── modul4/                        # Screenshot halaman Modul 4 (TelkomLibrary)
│   └── jurnal4/                       # Screenshot halaman Jurnal 4 (CeLOE LMS)
└── README.md                          # Dokumentasi utama repositori gabungan

```

## Bagian 1: Modul 4 — TelkomLibrary (`PAW_MODUL4_RAFFATA_102042500123`)

Sistem Peminjaman Buku Perpustakaan berbasis **Laravel 12** yang memperluas aplikasi dasar Modul 3 dengan pemisahan hak akses multi-peran, manajemen berkas sampul buku pada Laravel Storage, serta katalog publik interaktif. Sumber starter: [rrendyys/MODUL4_PRAKTIKUM-PAW](https://www.google.com/search?q=https://github.com/rrendyys/MODUL4_PRAKTIKUM-PAW).

### Fitur & Implementasi Teknis (14 Berkas / TODO 1–10)

1. **Skema Database & Model (`add_role_to_users_table`, `add_cover_to_buku_table`, `User.php`, `Buku.php`)**

   * Menambahkan kolom `role` (`enum('admin', 'mahasiswa')`, default `'mahasiswa'`) pada tabel `users` dan kolom `cover` (`nullable`) pada tabel `buku`.

   * Mengatur `$fillable` dan relasi `hasMany(Peminjaman::class)` pada `User` dan `Buku`.

2. **Otorisasi Multi-Role (`RoleMiddleware.php`, `bootstrap/app.php`, `routes/web.php`)**

   * Mendaftarkan alias middleware `'role'` di `bootstrap/app.php` dan memproteksi grup rute `/dashboard`, `/buku`, `/anggota`, dan `/peminjaman` dengan `middleware(['auth', 'role:admin'])`.

   * Mengalihkan akun `mahasiswa` ke beranda (`route('home')`) dengan pesan *flash error* apabila mencoba mengakses rute Admin.

3. **Upload, Pratinjau, & Penghapusan Sampul Buku (`BukuController.php`, `create.blade.php`, `edit.blade.php`, `index.blade.php`)**

   * Validasi berkas `nullable|image|mimes:jpeg,png,jpg|max:2048` dan penyimpanan ke `storage/app/public/buku` yang ditautkan ke `public/storage` melalui `php artisan storage:link`.

   * Menghapus sampul fisik lama via `Storage::disk('public')->delete()` saat sampul diganti atau data buku dihapus, serta menampilkan pratinjau instan (*client-side preview*) dan *thumbnail* di tabel Admin.

4. **Katalog Publik, Pencarian, Filter, & Paginasi (`HomeController.php`, `home.blade.php`, `home_show.blade.php`)**

   * Pencarian kondisional `when()` berdasarkan judul, penulis, atau penerbit, filter penerbit & status stok (`tersedia`/`habis`), pengurutan dinamis, serta `paginate(8)->withQueryString()`.

### Tampilan Antarmuka Modul 4 (TelkomLibrary)

> Letakkan file gambar `.png`/`.jpg` ke dalam folder `screenshots/modul4/` sesuai nama berkas di bawah agar otomatis tampil di GitHub.

| Halaman / Skenario Uji | Tampilan | 
| ----- | ----- | 
| **1. Katalog Buku Publik (`/` — Search, Filter, & Pagination)** |  | 
| **2. Detail Buku Publik (`/katalog/{id}`)** |  | 
| **3. Daftar Buku Admin (`/buku` — Thumbnail Sampul)** |  | 
| **4. Form Tambah & Edit Buku (Upload + Preview Sampul)** |  | 
| **5. Uji Proteksi `RoleMiddleware` (Mahasiswa Akses `/buku`)** |  | 

## Bagian 2: Jurnal 4 — CeLOE LMS (`PAW_JURNAL4_RAFFATA_102042500123`)

Sistem Manajemen Pembelajaran kampus berbasis **Laravel 12** yang berfokus pada pengamanan rute peran Dosen dan Mahasiswa, penyaringan katalog mata kuliah, penilaian tugas (*grading*), dan ekspor rekap nilai ke CSV. Sumber starter: [kal-ngga/JURNAL4_PRAKTIKUM-PAW](https://github.com/kal-ngga/JURNAL4_PRAKTIKUM-PAW).

### Fitur & Implementasi Teknis (TODO 1–8)

1. **Migrasi & Model Penilaian (`2026_10_25_000001_add_grading_to_submissions_table.php`, `Submission.php`)**

   * Menambahkan kolom `nilai` (`unsignedTinyInteger`, `nullable`) dan `catatan_dosen` (`text`, `nullable`) pada tabel `submissions` (**TODO 1**).

   * Menambahkan `$fillable` dan accessor `getStatusNilaiAttribute()` untuk menentukan status `'Belum Dinilai'`, `'Lulus'` (`>= 70`), atau `'Perlu Perbaikan'` (`< 70`) (**TODO 2**).

2. **Proteksi Rute Multi-Role (`routes/web.php`)**

   * Membungkus seluruh rute non-guest dalam `middleware('auth')` serta memisahkan grup `role:dosen` dan `role:mahasiswa`, sekaligus mengaktifkan rute `submissions.grade` (`PATCH`) dan `assignments.export-csv` (`GET`) (**TODO 3**).

3. **Filter Semester & Pengurutan Katalog (`MataKuliahController.php`, `courses/index.blade.php`)**

   * Filter berdasarkan `semester` serta pengurutan `nama_asc`, `nama_desc`, `sks_desc`, dan `sks_asc` dengan state dropdown tetap terjaga (**TODO 4 & TODO 6**).

4. **Penilaian Tugas, Statistik, & Ekspor CSV (`SubmissionController.php`, `assignments/submissions.blade.php`, `DatabaseSeeder.php`)**

   * Validasi nilai `0–100` dan `catatan_dosen` maksimal 1000 karakter pada `grade()`, serta streaming unduhan CSV via `response()->streamDownload()` dan `fputcsv()` pada `exportCsv()` (**TODO 5a & 5b**).

   * Kartu statistik pengumpulan (Total Mahasiswa, Sudah Mengumpulkan, Rata-rata Nilai), tombol **Export CSV**, dan form `PATCH` penilaian (**TODO 7a, 7b, 7c**), ditambah data uji pada `DatabaseSeeder` (**TODO 8**).

### Tampilan Antarmuka Jurnal 4 (CeLOE LMS)

> Letakkan file gambar `.png`/`.jpg` ke dalam folder `screenshots/jurnal4/` sesuai nama berkas di bawah agar otomatis tampil di GitHub.

| Halaman / Skenario Uji | Tampilan | 
| ----- | ----- | 
| **1. Katalog Mata Kuliah (`/courses` — Filter Semester & Sorting SKS)** |  | 
| **2. Uji Proteksi `RoleMiddleware` (Mahasiswa Akses `/courses/create` → `403 Forbidden`)** |  | 
| **3. Halaman Pengumpulan Tugas Dosen (Statistik, Export CSV, & Form Nilai)** |  | 
| **4. Hasil Penilaian (`Lulus` / `Perlu Perbaikan` & Rata-rata Nilai Terbarui)** |  | 
| **5. Hasil Ekspor Rekap Nilai CSV (`rekap-nilai-tugas.csv` di Excel/VS Code)** |  | 

## Panduan Instalasi & Pengujian

### A. Menjalankan Modul 4 (`PAW_MODUL4_RAFFATA_102042500123`)

```
cd PAW_MODUL4_RAFFATA_102042500123
composer install
cp .env.example .env
php artisan key:generate
# Atur DB_DATABASE=db_peminjamanbuku (sesuaikan DB_PORT & DB_PASSWORD di .env)
php artisan storage:link
php artisan migrate:fresh --seed
php artisan db:seed --class=AdminSeeder
php artisan test
php artisan serve

```

* **Admin**: `admin@telkomlibrary.ac.id` / `admin123`

* **Mahasiswa**: `mahasiswa@telkomlibrary.ac.id` / `mahasiswa123`

### B. Menjalankan Jurnal 4 (`PAW_JURNAL4_RAFFATA_102042500123`)

```
cd PAW_JURNAL4_RAFFATA_102042500123
composer install
cp .env.example .env
php artisan key:generate
# Atur DB_DATABASE=db_celoe_lms (sesuaikan DB_PORT & DB_PASSWORD di .env)
php artisan migrate:fresh --seed
php artisan serve

```

* **Dosen**: `dosen@celoe.test` / `dosen123` (NIP: `198501012015041001`)

* **Mahasiswa**: `mahasiswa@celoe.test` / `mahasiswa123` (NIM: `1301210001`)

## Tech Stack

* **Backend**: PHP >= 8.2, Laravel 12

* **Database**: MySQL / MariaDB (Port `3306` native atau `3307` Docker)

* **Frontend**: Laravel Blade, Bootstrap 5, Vite

* **Testing & Version Control**: PHPUnit 11, Git & GitHub