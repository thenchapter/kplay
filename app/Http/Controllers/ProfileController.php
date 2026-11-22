<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleSheetService;

class ProfileController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    /* ============================================================
     * HALAMAN UTAMA PROFIL
     * ============================================================ */

    public function index()
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        // Sinkronkan bias_ids dari sheet user_bias ke session
        $user['bias_ids'] = $this->getBiasIds($user);
        session(['user' => $user]);

        return view('profile.index', compact('user'));
    }

    /* ============================================================
     * UPLOAD FOTO PROFIL (MENGGUNAKAN URL)
     * ============================================================ */

       public function updatePhoto(Request $request)
    {
        // Validasi file gambar
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'photo.required' => 'Pilih foto terlebih dahulu.',
            'photo.image'    => 'File harus berupa gambar.',
            'photo.mimes'    => 'Format harus JPG, JPEG, PNG, atau WEBP.',
            'photo.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        $user = session('user');
        if (!$user) return redirect()->route('login');

        try {
            $userId = $user['id'] ?? null;

            // Hapus foto lama jika ada (opsional)
            if (!empty($user['avatar']) && file_exists(public_path('storage/' . $user['avatar']))) {
                @unlink(public_path('storage/' . $user['avatar']));
            }

            // Simpan file baru ke folder storage/app/public/avatars
            $path = $request->file('photo')->store('avatars', 'public');

            // Update ke Google Sheets (simpan path-nya saja, misal: "avatars/xxxxx.jpg")
            if ($userId) {
                $this->gs->update('users', $userId, ['avatar' => $path]);
            }

            // Update session agar langsung berubah
            $user['avatar'] = $path;
            session(['user' => $user]);

            return back()->with('success', 'Foto profil berhasil diperbarui! 📸');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Upload foto gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal upload foto. Coba lagi.');
        }
    }

    /* ============================================================
     * MY BIAS
     * ============================================================ */

    public function bias()
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        $biasIds = $this->getBiasIds($user);
        
        // Ambil semua idol dari Google Sheets
        $allIdols = $this->gs->getAll('idols') ?: [];

        // Filter hanya idol yang ada di biasIds
        $biases = array_values(array_filter($allIdols, function ($idol) use ($biasIds) {
            return in_array((string) ($idol['id'] ?? ''), $biasIds, true);
        }));

        // Sinkronkan session
        $user['bias_ids'] = $biasIds;
        session(['user' => $user]);

        return view('profile.bias', compact('user', 'biases', 'biasIds'));
    }

    public function addBias(Request $request)
    {
        $request->validate(['idol_id' => 'required']);

        $user = session('user');
        if (!$user) return redirect()->route('login');

        $userId  = $user['id'] ?? null;
        $idolId  = (string) $request->input('idol_id');

        if (!$userId) {
            return back()->with('error', 'Sesi user tidak valid. Silakan login ulang.');
        }

        // Cek duplikat
        $current = $this->getBiasIds($user);
        if (in_array($idolId, $current, true)) {
            return back()->with('error', 'Idol ini sudah ada di biasmu.');
        }

        // Cari group_id idol (opsional)
        $idol = $this->gs->getById('idols', $idolId) ?? [];
        $groupId = $idol['grup_id'] ?? $idol['group_id'] ?? null;

        // Simpan ke sheet user_bias
        $saved = $this->gs->addUserBias($userId, $idolId, $groupId);

        if ($saved) {
            $current[] = $idolId;
            $user['bias_ids'] = $current;
            session(['user' => $user]);
            return back()->with('success', 'Idol berhasil ditambahkan ke bias!');
        }

        return back()->with('error', 'Gagal menyimpan bias ke database. Coba lagi.');
    }

    public function removeBias(Request $request)
    {
        $request->validate(['idol_id' => 'required']);

        $user = session('user');
        if (!$user) return redirect()->route('login');

        $userId = $user['id'] ?? null;
        $idolId = (string) $request->input('idol_id');

        if (!$userId) {
            return back()->with('error', 'Sesi user tidak valid. Silakan login ulang.');
        }

        $saved = $this->gs->removeUserBias($userId, $idolId);

        if ($saved) {
            $current = $this->getBiasIds($user);
            $user['bias_ids'] = $current;
            session(['user' => $user]);
            return back()->with('success', 'Idol dihapus dari bias.');
        }

        return back()->with('error', 'Gagal menghapus bias. Coba lagi.');
    }

    /* ============================================================
     * GRUP FAVORIT / PLAYLIST / NOTIFIKASI
     * ============================================================ */

    public function groups()
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        $userId = $user['id'] ?? null;

        // Ambil semua grup dari Google Sheets
        $allGroups = $this->gs->getAll('groups') ?: [];

        // Ambil group_id yang di-follow user
        $followedIds = $userId
            ? $this->gs->getUserGroupIds($userId)
            : [];

        // Filter HANYA grup yang di-follow
        $groups = array_values(array_filter($allGroups, function ($g) use ($followedIds) {
            return in_array((string) ($g['id'] ?? ''), array_map('strval', $followedIds), true);
        }));

        return view('profile.groups', compact('user', 'groups', 'followedIds'));
    }

    public function playlist()
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        $userId = $user['id'] ?? null;

        // Ambil semua playlist dari sheet
        $allPlaylists = $this->gs->getAll('playlists') ?: [];

        // Filter hanya playlist milik user ini (atau tampilkan semua jika kosong)
        $playlists = array_values(array_filter($allPlaylists, function ($p) use ($userId) {
            return (string) ($p['user_id'] ?? '') === (string) $userId;
        }));

        return view('profile.playlist', compact('user', 'playlists'));
    }

    public function notifications()
    {
        $user = session('user');
        if (!$user) return redirect()->route('login');

        // Data notifikasi dummy (karena belum ada sheet notifications yang terisi)
        $notifications = [
            ['id'=>1,'type'=>'bias','title'=>'Idol favoritmu update!','message'=>'Lisa BLACKPINK baru saja merilis foto teaser untuk album terbaru.','time'=>now()->subMinutes(15)->timestamp,'read'=>false,'url'=>route('idols.index')],
            ['id'=>2,'type'=>'community','title'=>'Balasan baru di diskusi','message'=>'Seseorang membalas postinganmu di komunitas K-PLAY.','time'=>now()->subHours(2)->timestamp,'read'=>false,'url'=>route('community.index')],
            ['id'=>3,'type'=>'comeback','title'=>'Comeback terdekat','message'=>'NewJeans akan comeback dalam 3 hari. Siapkan dirimu!','time'=>now()->subHours(5)->timestamp,'read'=>true,'url'=>route('news.calendar')],
            ['id'=>4,'type'=>'system','title'=>'Selamat datang di K-PLAY','message'=>'Akunmu berhasil dibuat. Jelajahi idol, grup, dan komunitas sekarang!','time'=>now()->subDays(3)->timestamp,'read'=>true,'url'=>route('home')],
        ];

        $unreadCount = collect($notifications)->where('read', false)->count();

        return view('profile.notifications', compact('user', 'notifications', 'unreadCount'));
    }

    /* ============================================================
     * HELPER: BIAS
     * ============================================================ */

    protected function getBiasIds(array $user): array
    {
        // Prioritas: session dulu
        if (!empty($user['bias_ids'])) {
            $raw = $user['bias_ids'];
            return is_array($raw)
                ? array_values(array_map('strval', $raw))
                : array_values(array_filter(array_map('trim', explode(',', (string) $raw))));
        }

        // Fallback: dari sheet user_bias
        $userId = $user['id'] ?? null;
        if (!$userId) return [];

        return $this->gs->getUserBiasIds($userId);
    }
}