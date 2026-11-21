<div align="center">

# 📚 PAW Modul 4 & Jurnal 4 — Laravel 12 Lanjutan

**TelkomLibrary** *(Sistem Peminjaman Buku)* & **CeLOE LMS** *(Sistem Manajemen Pembelajaran)*

<p>
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5" />
  <img src="https://img.shields.io/badge/License-MIT-10B981?style=for-the-badge" alt="MIT License" />
</p>

<p>
  <a href="#-identitas-praktikan">Identitas</a> •
  <a href="#-modul-4--telkomlibrary">Modul 4 (TelkomLibrary)</a> •
  <a href="#-jurnal-4--celoe-lms">Jurnal 4 (CeLOE LMS)</a> •
  <a href="#-quick-start">Quick Start</a> •
  <a href="#-galeri-antarmuka">Screenshots</a>
</p>

</div>

---

## 👤 Identitas Praktikan

| Nama Lengkap | NIM | Kelas | Asisten | Dosen Pembimbing |
| :--- | :--- | :--- | :---: | :--- |
| **Raffata Izacky Yuargya Aletama** | `102042500123` | `S1-SI-KJ-25-02` | `MARK` | Dewi Marini Umi Atmaja, S.Kom., M.Kom. |

> **Program Studi S1 Sistem Informasi** — Fakultas Rekayasa Industri, Telkom University Jakarta

---

## 📂 Struktur Repositori

```text
PAW-MODUL4-RAFFATA-102042500123/
├── PAW_MODUL4_RAFFATA_102042500123/   # Modul 4: TelkomLibrary (RBAC, Upload Cover, Search & Pagination)
├── PAW_JURNAL4_RAFFATA_102042500123/  # Jurnal 4: CeLOE LMS (Middleware, Filter/Sort, Grading, CSV Export)
├── screenshots/                       # Dokumentasi tampilan aplikasi (modul4/ & jurnal4/)
├── LICENSE                            # Lisensi MIT
└── README.md                          # Dokumentasi utama repositori
```

---

## 📖 Modul 4 — TelkomLibrary

**Folder:** `PAW_MODUL4_RAFFATA_102042500123/` · **Starter:** [rrendyys/MODUL4_PRAKTIKUM-PAW](https://github.com/rrendyys/MODUL4_PRAKTIKUM-PAW)

Aplikasi manajemen perpustakaan kampus dengan pemisahan hak akses **Admin** dan **Mahasiswa**, manajemen berkas sampul buku pada *Laravel Storage*, serta katalog publik interaktif.

### Fitur Utama (`TODO 1` – `TODO 10`)
- **Multi-Role RBAC (`RoleMiddleware`)** — Alias `'role'` di `bootstrap/app.php` memproteksi rute `/dashboard`, `/buku`, `/anggota`, dan `/peminjaman` dengan `middleware(['auth', 'role:admin'])`. Akun Mahasiswa yang mengakses rute Admin dialihkan ke beranda dengan pesan *flash error*.
- **Manajemen Sampul Buku (`Storage`)** — Validasi gambar (`JPG/JPEG/PNG`, maks. `2 MB`), penyimpanan ke `storage/app/public/buku` via `php artisan storage:link`, pratinjau instan sebelum *submit*, serta penghapusan otomatis berkas sampul lama saat *update* atau *delete*.
- **Pencarian, Filter, & Paginasi (`HomeController`)** — Query kondisional `when()` berdasarkan judul/penulis/penerbit, filter penerbit & status stok (`tersedia`/`habis`), pengurutan dinamis, dan `paginate(8)->withQueryString()`.
- **Automated Feature Tests** — Pengujian PHPUnit (`RefreshDatabase`) untuk otorisasi `RoleMiddleware`, CRUD buku/anggota, sirkulasi peminjaman, dan katalog publik.

<details>
<summary>📋 <b>Lihat Daftar 14 Berkas yang Dikerjakan (Modul 4)</b></summary>

| Komponen | Berkas | Ringkasan Implementasi |
| :--- | :--- | :--- |
| **Migrations** | `add_role_to_users_table` & `add_cover_to_buku_table` | Tambah enum `role` (`admin`, `mahasiswa`) dan kolom `cover` (`nullable`) |
| **Models** | `User.php`, `Buku.php` | Tambah `$fillable` (`role`, `cover`) dan relasi `hasMany(Peminjaman::class)` |
| **Security** | `RoleMiddleware.php`, `bootstrap/app.php`, `routes/web.php` | Registrasi alias `'role'` & proteksi grup rute `role:admin` |
| **Controllers** | `HomeController.php`, `BukuController.php` | Search/filter/sort/pagination publik & upload/replace/delete cover |
| **Views** | `buku/create`, `buku/edit`, `buku/index`, `home`, `home_show` | Form `multipart/form-data`, preview gambar, thumbnail tabel, & katalog |

</details>

---

## 🎓 Jurnal 4 — CeLOE LMS

**Folder:** `PAW_JURNAL4_RAFFATA_102042500123/` · **Starter:** [kal-ngga/JURNAL4_PRAKTIKUM-PAW](https://github.com/kal-ngga/JURNAL4_PRAKTIKUM-PAW)

Learning Management System (LMS) berbasis **Laravel 12** dengan pemisahan peran **Dosen** dan **Mahasiswa**, filter/sorting katalog mata kuliah, penilaian tugas (*grading*), dan ekspor rekap nilai ke **CSV**.

### Fitur Utama (`TODO 1` – `TODO 8`)
- **Proteksi Rute Multi-Role (`routes/web.php`)** — Grup `auth` membungkus seluruh rute internal, lalu dipisah menjadi `role:dosen` (manajemen mata kuliah, materi, tugas, *approval*, penilaian, & ekspor CSV) dan `role:mahasiswa` (*enroll request* & *submit* tugas), dengan respons `403 Forbidden` bagi peran yang tidak berwenang.
- **Filter Semester & Sorting Katalog (`MataKuliahController`)** — Filter mata kuliah per `semester` dan pengurutan (`nama_asc`, `nama_desc`, `sks_desc`, `sks_asc`) pada `courses/index.blade.php`.
- **Penilaian Tugas & Accessor (`Submission` & `SubmissionController@grade`)** — Kolom `nilai` (`0–100`) dan `catatan_dosen` pada tabel `submissions`, accessor `status_nilai` (`Belum Dinilai`, `Lulus >= 70`, `Perlu Perbaikan`), kartu ringkasan statistik, dan formulir `PATCH` per mahasiswa.
- **Ekspor Rekap Nilai CSV (`SubmissionController@exportCsv`)** — Unduhan langsung berkas `.csv` (UTF-8 BOM, siap buka di Excel) menggunakan `response()->streamDownload()` dan `fputcsv()`.

<details>
<summary>📋 <b>Lihat Daftar 8 TODO yang Diselesaikan (Jurnal 4)</b></summary>

| TODO | Berkas Target | Ringkasan Implementasi |
| :-: | :--- | :--- |
| **1** | `2026_10_25_000001_add_grading_to_submissions_table.php` | Tambah kolom `nilai` (`unsignedTinyInteger`) & `catatan_dosen` (`text`) |
| **2** | `app/Models/Submission.php` | Tambah `$fillable` & accessor `getStatusNilaiAttribute()` |
| **3** | `routes/web.php` | Proteksi `auth`, `role:dosen`, `role:mahasiswa`, aktivasi `grade` & `export-csv` |
| **4** | `app/Http/Controllers/MataKuliahController.php` | Filter `semester` & sorting `nama_asc/desc`, `sks_asc/desc` |
| **5a–5b** | `app/Http/Controllers/SubmissionController.php` | Validasi penilaian `grade()` & streaming CSV `exportCsv()` |
| **6** | `resources/views/courses/index.blade.php` | Dropdown filter `semester` & `sort` |
| **7a–7c** | `resources/views/assignments/submissions.blade.php` | Kartu statistik, tombol Export CSV, & form `PATCH` penilaian |
| **8** | `database/seeders/DatabaseSeeder.php` | Tambah 2 mata kuliah, 1 mahasiswa, 2 tugas, & sampel submission |

</details>

---

## ⚡ Quick Start

### 1. Menjalankan Modul 4 (`TelkomLibrary`)
```bash
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

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@telkomlibrary.ac.id` | `admin123` |
| **Mahasiswa** | `mahasiswa@telkomlibrary.ac.id` | `mahasiswa123` |

### 2. Menjalankan Jurnal 4 (`CeLOE LMS`)
```bash
cd PAW_JURNAL4_RAFFATA_102042500123
composer install
cp .env.example .env
php artisan key:generate
# Atur DB_DATABASE=db_celoe_lms (sesuaikan DB_PORT & DB_PASSWORD di .env)
php artisan migrate:fresh --seed
php artisan serve
```

| Role | Email | Password | NIP / NIM |
| :--- | :--- | :--- | :--- |
| **Dosen** | `dosen@celoe.test` | `dosen123` | `198501012015041001` |
| **Mahasiswa** | `mahasiswa@celoe.test` | `mahasiswa123` | `1301210001` |

---

## 📸 Galeri Antarmuka

<details>
<summary><b>📂 Klik untuk melihat Screenshot Modul 4 (TelkomLibrary)</b></summary>
<br />

| Fitur / Skenario Uji | Preview |
| :--- | :--- |
| **Katalog Publik (`/` — Search, Filter, Pagination)** | ![Katalog Publik](screenshots/modul4/01-katalog-publik.png) |
| **Detail Buku Publik (`/katalog/{id}`)** | ![Detail Buku](screenshots/modul4/02-detail-buku-publik.png) |
| **Daftar Buku Admin (`/buku` — Thumbnail Sampul)** | ![Daftar Buku Admin](screenshots/modul4/03-daftar-buku-admin.png) |
| **Form Upload & Preview Sampul** | ![Upload Sampul](screenshots/modul4/04-upload-sampul.png) |
| **Uji `RoleMiddleware` (Mahasiswa Akses `/buku`)** | ![Uji RoleMiddleware](screenshots/modul4/05-uji-role-middleware.png) |

</details>

<details>
<summary><b>📂 Klik untuk melihat Screenshot Jurnal 4 (CeLOE LMS)</b></summary>
<br />

| Fitur / Skenario Uji | Preview |
| :--- | :--- |
| **Katalog Mata Kuliah (`/courses` — Filter & Sort)** | ![Katalog Mata Kuliah](screenshots/jurnal4/01-katalog-matakuliah.png) |
| **Uji `RoleMiddleware` (`403 Forbidden`)** | ![Uji 403](screenshots/jurnal4/02-uji-role-403.png) |
| **Halaman Submissions Dosen (Statistik & Form Nilai)** | ![Halaman Submissions](screenshots/jurnal4/03-halaman-submissions.png) |
| **Hasil Penilaian (`Lulus` / `Perlu Perbaikan`)** | ![Hasil Grading](screenshots/jurnal4/04-hasil-grading.png) |
| **Hasil Ekspor Rekap Nilai CSV** | ![Ekspor CSV](screenshots/jurnal4/05-ekspor-csv.png) |

</details>

---

<div align="center">
  <sub>Disusun oleh <a href="https://github.com/Dex-toxicabyss">Raffata Izacky Yuargya Aletama</a> · Telkom University Jakarta · Lisensi <a href="LICENSE">MIT</a></sub>
</div>