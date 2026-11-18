# TelkomLibrary — Sistem Peminjaman Buku Perpustakaan (Modul 4 PAW)

Aplikasi web manajemen sirkulasi dan katalog perpustakaan kampus berbasis **Laravel 12** yang dilengkapi otorisasi multi-peran (**Role-Based Access Control** untuk `admin` dan `mahasiswa`), manajemen berkas sampul buku (**Laravel Storage & Symbolic Link**), serta katalog publik interaktif dengan pencarian multi-kolom, penyaringan stok/penerbit, pengurutan dinamis, dan paginasi.

---

## Identitas Pengembang

| Atribut | Detail |
| :--- | :--- |
| **Nama Lengkap** | Raffata Izacky Yuargya Aletama |
| **NIM** | 102042500123 |
| **Kelas** | S1-SI-KJ-25-02 |
| **Kode Asisten** | MARK |
| **Mata Kuliah** | Pengembangan Aplikasi Website (Modul 4 — Laravel Lanjutan) |
| **Program Studi** | S1 Sistem Informasi — Fakultas Rekayasa Industri, Telkom University Jakarta |

---

## Fitur Utama

1. **Multi-Role Authentication & Authorization (`RoleMiddleware`)**
   - Pemisahan hak akses antara **Admin Perpustakaan** (`admin`), **Mahasiswa** (`mahasiswa`), dan **Pengunjung Publik** (`guest`).
   - Proteksi grup rute manajemen (`/dashboard`, `/buku`, `/anggota`, `/peminjaman`) menggunakan middleware alias `role:admin`.
   - Pengalihan otomatis (*redirect*) beserta pesan *flash error* apabila akun Mahasiswa mencoba mengakses URL khusus Admin.

2. **Manajemen Unggah Sampul Buku (`Storage` & `multipart/form-data`)**
   - Validasi berkas gambar sampul (`nullable|image|mimes:jpeg,png,jpg|max:2048`).
   - Penyimpanan terisolasi ke disk `storage/app/public/buku` yang diakses publik melalui *symbolic link* `public/storage`.
   - Pratinjau gambar instan (*live client-side image preview*) sebelum formulir dikirim.
   - Pembersihan otomatis berkas sampul lama (`Storage::disk('public')->delete()`) saat sampul diperbarui atau data buku dihapus.

3. **Katalog Publik, Pencarian Dinamis, Filter, & Paginasi**
   - Pencarian *real-time* berdasarkan **Judul Buku**, **Penulis**, atau **Penerbit** menggunakan metode kondisional `when()`.
   - Filter berdasarkan **Penerbit** dan **Status Ketersediaan Stok** (`Stok Tersedia` / `Stok Habis`) serta pengurutan (`A-Z`, `Z-A`, `Tahun`, `Stok`).
   - Paginasi (`paginate()->withQueryString()`) dengan tampilan *Bootstrap 5* yang mempertahankan parameter filter dan pencarian di URL.

4. **Transaksi Sirkulasi Peminjaman & Pengembalian Buku**
   - Dekremen stok otomatis ketika buku dipinjam dan inkremen stok otomatis saat buku dikembalikan.
   - Proteksi penghapusan buku apabila masih terdapat transaksi peminjaman berstatus `Dipinjam`.

---

## Tech Stack

- **Backend**: PHP 8.2+, Laravel 12.x
- **Database**: MySQL / MariaDB (`db_peminjamanbuku`)
- **Frontend**: Blade Templating Engine, Bootstrap 5.3.3, Bootstrap Icons 1.11.3
- **Testing**: PHPUnit 11 (`RefreshDatabase` Feature Tests)

---

## Panduan Instalasi Cepat (Quick Start)

1. **Clone repositori dan masuk ke direktori proyek:**
   ```bash
   git clone https://github.com/Dex-toxicabyss/PAW-MODUL4-RAFFATA-102042500123.git
   cd PAW-MODUL4-RAFFATA-102042500123
   ```

2. **Instal dependensi PHP dan salin konfigurasi environment:**
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Konfigurasi koneksi database pada berkas `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_peminjamanbuku
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Buat symbolic link penyimpanan publik, jalankan migrasi, dan isi data awal (Seeder):**
   ```bash
   php artisan storage:link
   php artisan migrate
   php artisan db:seed --class=AdminSeeder
   ```

5. **Jalankan server pengembangan lokal:**
   ```bash
   php artisan serve
   ```
   Akses aplikasi melalui peramban di `http://127.0.0.1:8000`.

---

## Akun Pengujian Default (`AdminSeeder`)

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@telkomlibrary.ac.id` | `admin123` | Akses penuh Dashboard, CRUD Buku & Upload Cover, Anggota, dan Sirkulasi Peminjaman |
| **Mahasiswa** | `mahasiswa@telkomlibrary.ac.id` | `mahasiswa123` | Akses Katalog Publik, Pencarian/Filter, dan Detail Buku |

---

## Daftar 14 Berkas Implementasi Modul 4

| No | Path Berkas | Deskripsi Perubahan |
| :-: | :--- | :--- |
| 1 | `database/migrations/2026_11_18_031914_add_role_to_users_table.php` | Migrasi *alter table* mengubah kolom `role` pada tabel `users` menjadi `enum('admin', 'mahasiswa')` |
| 2 | `database/migrations/2026_11_18_031929_add_cover_to_buku_table.php` | Migrasi *alter table* menambahkan kolom `cover` (`nullable`) setelah kolom `stok` pada tabel `buku` |
| 3 | `app/Models/User.php` | Menambahkan atribut `role` pada `$fillable` dan relasi `hasMany(Peminjaman::class)` (TODO 1) |
| 4 | `app/Models/Buku.php` | Menambahkan atribut `cover` pada `$fillable` (TODO 2) |
| 5 | `app/Http/Middleware/RoleMiddleware.php` | Middleware kustom untuk memvalidasi peran pengguna dan menolak akses yang tidak sesuai |
| 6 | `bootstrap/app.php` | Mendaftarkan alias middleware `'role' => RoleMiddleware::class` |
| 7 | `routes/web.php` | Menambahkan proteksi `middleware(['auth', 'role:admin'])` pada grup rute manajemen admin |
| 8 | `app/Http/Controllers/HomeController.php` | Implementasi pencarian `when()`, filter penerbit & stok, pengurutan, paginasi, dan halaman detail (TODO 3) |
| 9 | `app/Http/Controllers/BukuController.php` | Validasi, penyimpanan, pembaruan, dan penghapusan fisik sampul buku pada `Storage::disk('public')` (TODO 4, 5, 6) |
| 10 | `resources/views/buku/create.blade.php` | Penambahan `enctype="multipart/form-data"`, input file `cover`, dan pratinjau gambar (TODO 7) |
| 11 | `resources/views/buku/edit.blade.php` | Penambahan `enctype="multipart/form-data"`, pratinjau sampul saat ini, dan input ganti sampul (TODO 8) |
| 12 | `resources/views/buku/index.blade.php` | Penambahan kolom dan *thumbnail* sampul buku pada tabel daftar buku Admin (TODO 9) |
| 13 | `resources/views/home.blade.php` | Katalog buku publik dengan form pencarian, filter penerbit/status, *sorting*, dan paginasi (TODO 10) |
| 14 | `resources/views/home_show.blade.php` | Halaman detail informasi buku dan status ketersediaan eksemplar bagi publik/mahasiswa |

---

## Pengujian Otomatis (Automated Testing)

Jalankan rangkaian pengujian fitur (*Feature Tests*) menggunakan PHPUnit:

```bash
php artisan test
```
