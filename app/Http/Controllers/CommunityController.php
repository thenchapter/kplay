<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetService;

class CommunityController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    /**
     * Halaman utama: Menampilkan daftar Komunitas (Fandom).
     * Jika sheet 'community' kosong, halaman akan menampilkan pesan kosong.
     */
    public function index()
    {
        // Ambil data langsung dari sheet 'community'
        $communities = $this->gs->getAll('community');
        
        // Map data untuk memastikan format konsisten dengan view
        $fandoms = collect($communities)->map(function($c) {
            return [
                'id' => $c['id'] ?? 0,
                'slug' => $c['slug'] ?? strtolower($c['nama'] ?? ''),
                'nama' => strtoupper($c['nama'] ?? ''),
                'logo' => $c['image'] ?? '', // Sesuaikan dengan header sheet Anda
                'warna' => $c['warna'] ?? 'from-purple-500 to-pink-500',
                'member_count' => $c['member_count'] ?? 0,
                'deskripsi' => $c['description'] ?? 'Komunitas penggemar ' . ($c['nama'] ?? ''),
            ];
        });

        // Ambil ID grup yang sudah di-follow user dari session (atau Sheets jika ada)
        $followed = session('followed_fandoms', []);

        return view('community.index', compact('fandoms', 'followed'));
    }

    /**
     * Halaman Feed: Menampilkan postingan di dalam satu komunitas.
     */
    public function show($slug)
    {
        // Cari komunitas berdasarkan slug dari sheet 'community'
        $communities = $this->gs->getAll('community');
        $community = collect($communities)->firstWhere('slug', $slug);

        if (!$community) {
            abort(404, 'Komunitas tidak ditemukan');
        }

        // Ambil semua postingan dan filter berdasarkan community_id
        $allPosts = $this->gs->getAll('posts');
        $posts = collect($allPosts)->where('community_id', $community['id'])->sortByDesc('created_at')->values()->all();

        // Ambil data user untuk ditampilkan di postingan (mapping)
        $users = collect($this->gs->getAll('users'))->keyBy('id');

        $fandom = [
            'id' => $community['id'] ?? 0,
            'slug' => $slug,
            'nama' => $community['nama'] ?? '',
            'logo' => $community['image'] ?? '',
            'warna' => $community['warna'] ?? 'from-purple-500 to-pink-500',
            'member_count' => $community['member_count'] ?? 0,
            'deskripsi' => $community['description'] ?? 'Komunitas penggemar ' . ($community['nama'] ?? ''),
        ];

        return view('community.show', compact('fandom', 'posts', 'users'));
    }

    /**
     * Aksi untuk menyimpan postingan baru dari user.
     */
    public function storePost(Request $request, $slug)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $request->validate([
            'isi' => 'required|string|max:1000',
        ]);

        // Cari community_id berdasarkan slug
        $communities = $this->gs->getAll('community');
        $community = collect($communities)->firstWhere('slug', $slug);

        if ($community) {
            $this->gs->insert('posts', [
                'user_id' => $user['id'],
                'community_id' => $community['id'], // Sesuaikan dengan relasi baru
                'isi' => $request->isi,
                'image' => $request->gambar ?? '', 
                'likes' => 0,
                'comments_count' => 0,
                'created_at' => now()->toIso8601String(),
            ]);

            return redirect()->route('community.show', $slug)->with('success', 'Postingan berhasil dibuat! 💜');
        }

        return back()->with('error', 'Gagal membuat postingan.');
    }
}