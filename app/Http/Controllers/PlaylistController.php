<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Sx;
use App\Services\GoogleSheetService;

class PlaylistController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    public function index()
    {
        $playlists = Sx::all('playlists') ?: [];
        $groups    = Sx::all('groups') ?: [];

        usort($playlists, function ($a, $b) {
            return ((int) ($a['group_id'] ?? 0)) <=> ((int) ($b['group_id'] ?? 0));
        });

        // Like data
        $user       = session('user');
        $userId     = $user['id'] ?? null;
        $likedIds   = $userId ? $this->gs->getUserLikedSongIds($userId) : [];
        $likeCounts = $this->gs->getSongLikeCounts();

        return view('pages.playlist', compact(
            'playlists',
            'groups',
            'likedIds',
            'likeCounts'
        ));
    }

    public function like(Request $request)
    {
        $request->validate(['playlist_id' => 'required']);

        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Login required'], 401);
        }

        $userId = $user['id'] ?? null;
        if (!$userId) {
            return response()->json(['error' => 'Invalid session'], 400);
        }

        $playlistId = (string) $request->input('playlist_id');
        $ok = $this->gs->likeSong($userId, $playlistId);

        $counts = $this->gs->getSongLikeCounts();

        return response()->json([
            'success' => $ok,
            'liked'   => $ok,
            'count'   => $counts[$playlistId] ?? 0,
        ]);
    }

    public function unlike(Request $request)
    {
        $request->validate(['playlist_id' => 'required']);

        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Login required'], 401);
        }

        $userId = $user['id'] ?? null;
        if (!$userId) {
            return response()->json(['error' => 'Invalid session'], 400);
        }

        $playlistId = (string) $request->input('playlist_id');
        $ok = $this->gs->unlikeSong($userId, $playlistId);

        $counts = $this->gs->getSongLikeCounts();

        return response()->json([
            'success' => $ok,
            'liked'   => false,
            'count'   => $counts[$playlistId] ?? 0,
        ]);
    }
}