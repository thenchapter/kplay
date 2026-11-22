<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetService;
use Carbon\Carbon;

class NewsController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    /**
     * Menampilkan daftar berita
     */
    public function index()
    {
        $newsData = $this->gs->getAll('news');

        // Urutkan berdasarkan kolom 'tanggal' (terbaru di atas)
        $news = collect($newsData)->sortByDesc(function($item) {
            return $item['tanggal'] ?? $item['created_at'] ?? '';
        })->values();

        return view('pages.news', compact('news'));
    }

    /**
     * Menampilkan detail berita
     */
    public function show($id)
    {
        $article = $this->gs->getById('news', $id);

        if (!$article) {
            abort(404, 'Berita tidak ditemukan');
        }

        return view('pages.news-show', compact('article'));
    }

    /**
     * Menampilkan Kalender Comeback
     */
    public function calendar()
    {
        // Ambil data dari sheet 'comebacks'
        $comebacksData = $this->gs->getAll('comebacks');
        
        // Ambil data grup untuk mapping grup_id -> nama grup
        $groupsData = $this->gs->getAll('groups');
        $groups = collect($groupsData)->keyBy('id'); // Ubah jadi array dengan key 'id'

        $upcoming = [];
        $past = [];
        $now = Carbon::now();

        foreach ($comebacksData as $c) {
            $dateString = $c['tanggal'] ?? $c['date'] ?? $c['release_date'] ?? null;
            
            if ($dateString) {
                try {
                    $date = Carbon::parse($dateString);
                    $c['_t'] = $date->timestamp;

                    // Ambil nama grup dari data groups berdasarkan grup_id
                    $groupId = $c['grup_id'] ?? null;
                    $c['grup'] = $groups[$groupId]['nama'] ?? 'Grup Tidak Diketahui';

                    if ($date->isToday() || $date->isFuture()) {
                        $upcoming[] = $c;
                    } else {
                        $past[] = $c;
                    }
                } catch (\Exception $e) {
                    // Abaikan baris jika format tanggal tidak valid
                    continue;
                }
            }
        }

        // Urutkan data: 
        // Akan datang (terdekat ke terlama)
        usort($upcoming, fn($a, $b) => $a['_t'] <=> $b['_t']);
        // Sudah rilis (terbaru ke terlama)
        usort($past, fn($a, $b) => $b['_t'] <=> $a['_t']);

        return view('pages.calendar', compact('upcoming', 'past'));
    }
}