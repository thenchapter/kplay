<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected GoogleSheetService $gs;

    /**
     * Cache TTL (detik). 5 menit = 300 detik.
     * Ubah ke 0 jika ingin selalu fresh dari Google Sheets.
     */
    protected int $cacheTtl = 300;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    /**
     * Halaman utama (home).
     * Route: GET / → name: home
     */
    public function index()
    {
        // Beri waktu lebih jika Google Sheets lambat merespon
        @set_time_limit(120);

        $data = $this->loadHomeData();

        return view('home', $data);
    }

    /**
     * Ambil semua data untuk home, dengan caching & fallback.
     */
    protected function loadHomeData(): array
    {
        $cacheKey = 'home_data_v1';

        // Jika cache TTL = 0, skip cache
        if ($this->cacheTtl <= 0) {
            return $this->fetchFromSheet();
        }

        // Coba ambil dari cache dulu
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // Cache miss → fetch dari Google Sheets
        $data = $this->fetchFromSheet();

        // Simpan ke cache hanya jika tidak ada error
        if (empty($data['apiError'])) {
            Cache::put($cacheKey, $data, $this->cacheTtl);
        }

        return $data;
    }

    /**
     * Fetch data dari Google Sheets dengan try/catch.
     */
    protected function fetchFromSheet(): array
    {
        try {
            // Murni mengambil dari Google Sheets
            $trending  = $this->safeSlice($this->gs->getAll('idols'), 0, 6);
            $news      = $this->safeSlice($this->gs->getAll('news'), 0, 3);
            $groups    = $this->safeSlice($this->gs->getAll('groups'), 0, 6);
            $posts     = $this->safeSlice($this->gs->getAll('posts'), 0, 3);
            $comebacks = $this->safeSlice($this->gs->getAll('comebacks'), 0, 4);

            return [
                'trending'  => $trending,
                'news'      => $news,
                'groups'    => $groups,
                'posts'     => $posts,
                'comebacks' => $comebacks,
                'apiError'  => null,
            ];
        } catch (\Throwable $e) {
            // Jika internet mati / Google Script error, halaman TIDAK akan putih kosong
            Log::error('Gagal load Google Sheets: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'trending'  => [],
                'news'      => [],
                'groups'    => [],
                'posts'     => [],
                'comebacks' => [],
                'apiError'  => 'Koneksi ke database Google Sheets sedang gangguan. Silakan muat ulang halaman.',
            ];
        }
    }

    /**
     * Helper: array_slice yang aman jika data bukan array.
     */
    protected function safeSlice($data, int $offset, int $length): array
    {
        if (!is_array($data)) {
            return [];
        }

        return array_slice($data, $offset, $length);
    }
}