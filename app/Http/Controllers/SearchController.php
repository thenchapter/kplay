<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    public function index(Request $request)
    {
        set_time_limit(300);
        $q = trim((string) $request->query('q', ''));
        $results = [];

        if ($q !== '') {
            $idols = $this->gs->getAll('idols');
            foreach ($idols as $item) {
                if (stripos($item['nama'] ?? '', $q) !== false) {
                    $item['type'] = 'Idol';
                    $item['url']  = route('idols.show', $item['id']);
                    $results[] = $item;
                }
            }

            $news = $this->gs->getAll('news');
            foreach ($news as $item) {
                if (stripos($item['judul'] ?? '', $q) !== false) {
                    $item['type'] = 'News';
                    $item['url']  = route('news.index'); 
                    $results[] = $item;
                }
            }
        }

        return view('search.index', compact('results', 'q'));
    }
}