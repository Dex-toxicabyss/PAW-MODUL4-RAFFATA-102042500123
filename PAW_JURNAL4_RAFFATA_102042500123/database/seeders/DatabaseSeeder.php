<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Rahma',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        // 3. Mata Kuliah Dasar & Tambahan (berbagai semester & SKS)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'SI302'],
            [
                'nama_mk' => 'Sistem Basis Data Lanjut',
                'deskripsi' => 'Perancangan skema relasional, normalisasi, indexing, serta optimasi query pada DBMS modern.',
                'sks' => 3,
                'semester' => 'Genap 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'SI304'],
            [
                'nama_mk' => 'Keamanan Sistem Informasi',
                'deskripsi' => 'Pengantar autentikasi, otorisasi berbasis peran (RBAC), enkripsi, dan mitigasi kerentanan aplikasi web.',
                'sks' => 2,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // 6. Tambahkan Tugas & Sampel Pengumpulan (Submissions)
        $assignment1 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Tugas 1: Implementasi RoleMiddleware & Proteksi Route'],
            [
                'deskripsi' => 'Jelaskan cara kerja RoleMiddleware dalam memisahkan hak akses antara peran Dosen dan Mahasiswa pada Laravel 12.',
                'deadline' => now()->addDays(7),
            ]
        );

        $assignment2 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Tugas 2: Query Filter, Sorting, dan Ekspor CSV'],
            [
                'deskripsi' => 'Implementasikan filter semester dan streaming CSV menggunakan response()->streamDownload() serta fputcsv().',
                'deadline' => now()->addDays(14),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa1->id],
            [
                'jawaban' => 'RoleMiddleware memeriksa apakah pengguna sudah login melalui Auth::check() lalu mencocokkan Auth::user()->role terhadap daftar peran yang diizinkan.',
                'nilai' => 88,
                'catatan_dosen' => 'Penjelasan alur middleware sudah tepat dan terstruktur.',
                'submitted_at' => now()->subHours(5),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa2->id],
            [
                'jawaban' => 'Middleware didaftarkan sebagai alias role pada bootstrap/app.php lalu diterapkan ke grup route dosen dan mahasiswa.',
                'nilai' => null,
                'catatan_dosen' => null,
                'submitted_at' => now()->subHours(2),
            ]
        );
    }
}
