<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetService;

class GroupController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    public function index()
    {
        // ⬇️ Data group & idol diambil langsung dari Google Sheet, tiap request.
        $groups = $this->gs->getAll('groups') ?: [];
        $idols  = $this->gs->getAll('idols')  ?: [];

        $user        = session('user');
        $followedIds = [];

        if (is_array($user) && !empty($user['id'])) {
            $followedIds = $this->gs->getUserGroupIds($user['id']);
        }

        return view('pages.groups', compact('groups', 'idols', 'followedIds'));
    }

    public function follow(Request $request)
    {
        $request->validate(['group_id' => 'required']);

        $user = session('user');
        if (!is_array($user)) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId  = $user['id'] ?? null;
        $groupId = (string) $request->input('group_id');

        if (!$userId) {
            return back()->with('error', 'Sesi user tidak valid.');
        }

        $ok = $this->gs->followGroup($userId, $groupId);

        return $ok
            ? back()->with('success', 'Berhasil mengikuti grup!')
            : back()->with('error', 'Gagal mengikuti grup. Coba lagi.');
    }

    public function unfollow(Request $request)
    {
        $request->validate(['group_id' => 'required']);

        $user = session('user');
        if (!is_array($user)) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId  = $user['id'] ?? null;
        $groupId = (string) $request->input('group_id');

        if (!$userId) {
            return back()->with('error', 'Sesi user tidak valid.');
        }

        $ok = $this->gs->unfollowGroup($userId, $groupId);

        return $ok
            ? back()->with('success', 'Berhasil unfollow grup.')
            : back()->with('error', 'Gagal unfollow grup. Coba lagi.');
    }
}