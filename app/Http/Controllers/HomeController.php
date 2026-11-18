<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman katalog buku dengan fitur pencarian, filter, sorting, dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $penerbit = $request->input('penerbit');
        $status = $request->input('status');
        $sort = $request->input('sort');

        $query = Buku::query()
            // 1. Pencarian berdasarkan judul, penulis, atau penerbit
            ->when($search, function ($q, $search) {
                return $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('judul', 'like', "%{$search}%")
                             ->orWhere('penulis', 'like', "%{$search}%")
                             ->orWhere('penerbit', 'like', "%{$search}%");
                });
            })
            // 2. Filter penerbit
            ->when($penerbit, function ($q, $penerbit) {
                return $q->where('penerbit', $penerbit);
            })
            // 3. Filter ketersediaan stok
            ->when($status, function ($q, $status) {
                if ($status === 'tersedia') {
                    return $q->where('stok', '>', 0);
                } elseif ($status === 'habis') {
                    return $q->where('stok', '<=', 0);
                }
            });

        // 4. Pengurutan data berdasarkan input 'sort'
        switch ($sort) {
            case 'judul_asc':
                $query->orderBy('judul', 'asc');
                break;
            case 'judul_desc':
                $query->orderBy('judul', 'desc');
                break;
            case 'tahun_desc':
                $query->orderBy('tahun_terbit', 'desc');
                break;
            case 'tahun_asc':
                $query->orderBy('tahun_terbit', 'asc');
                break;
            case 'stok_desc':
                $query->orderBy('stok', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // 5. Paginasi dengan mempertahankan query string
        $buku = $query->paginate(8)->withQueryString();

        // 6. Ambil list penerbit unik untuk opsi dropdown filter
        $listPenerbit = Buku::select('penerbit')
            ->distinct()
            ->orderBy('penerbit', 'asc')
            ->pluck('penerbit');

        return view('home', compact('buku', 'listPenerbit'));
    }

    /**
     * Menampilkan halaman detail informasi sebuah buku untuk publik/mahasiswa.
     */
    public function show(Buku $buku)
    {
        return view('home_show', compact('buku'));
    }
}
