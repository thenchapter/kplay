<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    protected string $url;
    protected string $key;

        public function __construct()
    {
        $this->url = config('services.gsheet.url');
        $this->key = config('services.gsheet.key');
    }

    /* ============================================================
     * READ
     * ============================================================ */

    public function getAll(string $sheet): array
    {
        $res = $this->get(['action' => 'getAll', 'sheet' => $sheet, 'key' => $this->key]);
        return $res['data'] ?? [];
    }

    public function getById(string $sheet, $id): ?array
    {
        $res = $this->get(['action' => 'getById', 'sheet' => $sheet, 'id' => $id, 'key' => $this->key]);
        return $res['data'] ?? null;
    }

    public function getWhere(string $sheet, string $field, $value): array
    {
        $res = $this->get(['action' => 'getWhere', 'sheet' => $sheet, 'field' => $field, 'value' => $value, 'key' => $this->key]);
        return $res['data'] ?? [];
    }

    public function search(string $sheet, string $q): array
    {
        $res = $this->get(['action' => 'search', 'sheet' => $sheet, 'q' => $q, 'key' => $this->key]);
        return $res['data'] ?? [];
    }

    /* ============================================================
     * WRITE
     * ============================================================ */

    public function insert(string $sheet, array $data): ?int
    {
        $res = $this->post(['action' => 'insert', 'sheet' => $sheet, 'data' => $data, 'key' => $this->key]);
        return $res['id'] ?? null;
    }

    public function update(string $sheet, $id, array $data): bool
    {
        $res = $this->post(['action' => 'update', 'sheet' => $sheet, 'id' => $id, 'data' => $data, 'key' => $this->key]);
        return $res['success'] ?? false;
    }

    public function delete(string $sheet, $id): bool
    {
        $res = $this->post(['action' => 'delete', 'sheet' => $sheet, 'id' => $id, 'key' => $this->key]);
        return $res['success'] ?? false;
    }

    /* ============================================================
     * HELPER KHUSUS
     * ============================================================ */

    public function updateRow(string $sheet, $id, array $data): bool
    {
        return $this->update($sheet, $id, $data);
    }

    public function findUser($userId): ?array
    {
        $user = $this->getById('users', $userId);
        if ($user) return $user;

        $users = $this->getWhere('users', 'username', $userId);
        return $users[0] ?? null;
    }

    /* ============================================================
     * USER BIAS (sheet user_bias)
     * ============================================================ */

    public function getUserBiasIds($userId): array
    {
        try {
            $rows = $this->getWhere('user_bias', 'user_id', $userId);
            return array_values(array_map(
                fn($r) => (string) ($r['idol_id'] ?? ''),
                array_filter($rows, fn($r) => !empty($r['idol_id']))
            ));
        } catch (\Throwable $e) {
            Log::error('getUserBiasIds error: ' . $e->getMessage());
            return [];
        }
    }

    public function addUserBias($userId, $idolId, $groupId = null): bool
    {
        try {
            $existing = $this->getWhere('user_bias', 'user_id', $userId);
            foreach ($existing as $row) {
                if ((string) ($row['idol_id'] ?? '') === (string) $idolId) {
                    return true;
                }
            }

            $id = $this->insert('user_bias', [
                'user_id'  => $userId,
                'idol_id'  => $idolId,
                'group_id' => $groupId,
            ]);

            return $id !== null;
        } catch (\Throwable $e) {
            Log::error('addUserBias error: ' . $e->getMessage());
            return false;
        }
    }

    public function removeUserBias($userId, $idolId): bool
    {
        try {
            $rows = $this->getWhere('user_bias', 'user_id', $userId);

            foreach ($rows as $row) {
                if ((string) ($row['idol_id'] ?? '') === (string) $idolId) {
                    return $this->delete('user_bias', $row['id']);
                }
            }

            return false;
        } catch (\Throwable $e) {
            Log::error('removeUserBias error: ' . $e->getMessage());
            return false;
        }
    }

    /* ============================================================
     * USER GROUPS (follow grup)
     * ============================================================ */

    public function getUserGroupIds($userId): array
    {
        try {
            $rows = $this->getWhere('user_groups', 'user_id', $userId);
            return array_values(array_map(
                fn($r) => (string) ($r['group_id'] ?? ''),
                array_filter($rows, fn($r) => !empty($r['group_id']))
            ));
        } catch (\Throwable $e) {
            Log::error('getUserGroupIds error: ' . $e->getMessage());
            return [];
        }
    }

    public function followGroup($userId, $groupId): bool
    {
        try {
            $existing = $this->getWhere('user_groups', 'user_id', $userId);
            foreach ($existing as $row) {
                if ((string) ($row['group_id'] ?? '') === (string) $groupId) {
                    return true;
                }
            }

            $id = $this->insert('user_groups', [
                'user_id'    => $userId,
                'group_id'   => $groupId,
                'created_at' => now()->toIso8601String(),
            ]);

            return $id !== null;
        } catch (\Throwable $e) {
            Log::error('followGroup error: ' . $e->getMessage());
            return false;
        }
    }

    public function unfollowGroup($userId, $groupId): bool
    {
        try {
            $rows = $this->getWhere('user_groups', 'user_id', $userId);

            foreach ($rows as $row) {
                if ((string) ($row['group_id'] ?? '') === (string) $groupId) {
                    return $this->delete('user_groups', $row['id']);
                }
            }

            return false;
        } catch (\Throwable $e) {
            Log::error('unfollowGroup error: ' . $e->getMessage());
            return false;
        }
    }

    /* ============================================================
     * USER PLAYLIST (like lagu) — BARU
     * ============================================================ */

    /**
     * Ambil semua playlist_id yang di-like user.
     */
    public function getUserLikedSongIds($userId): array
    {
        try {
            $rows = $this->getWhere('user_playlist', 'user_id', $userId);
            return array_values(array_map(
                fn($r) => (string) ($r['playlist_id'] ?? ''),
                array_filter($rows, fn($r) => !empty($r['playlist_id']))
            ));
        } catch (\Throwable $e) {
            Log::error('getUserLikedSongIds error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Like lagu.
     */
    public function likeSong($userId, $playlistId): bool
    {
        try {
            // Cek duplikat
            $existing = $this->getWhere('user_playlist', 'user_id', $userId);
            foreach ($existing as $row) {
                if ((string) ($row['playlist_id'] ?? '') === (string) $playlistId) {
                    return true;
                }
            }

            $id = $this->insert('user_playlist', [
                'user_id'     => $userId,
                'playlist_id' => $playlistId,
                'created_at'  => now()->toIso8601String(),
            ]);

            return $id !== null;
        } catch (\Throwable $e) {
            Log::error('likeSong error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Unlike lagu.
     */
    public function unlikeSong($userId, $playlistId): bool
    {
        try {
            $rows = $this->getWhere('user_playlist', 'user_id', $userId);

            foreach ($rows as $row) {
                if ((string) ($row['playlist_id'] ?? '') === (string) $playlistId) {
                    return $this->delete('user_playlist', $row['id']);
                }
            }

            return false;
        } catch (\Throwable $e) {
            Log::error('unlikeSong error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hitung total like per lagu.
     * Return: ['playlist_id' => count, ...]
     */
    public function getSongLikeCounts(): array
    {
        try {
            $rows = $this->getAll('user_playlist');
            $counts = [];
            foreach ($rows as $row) {
                $pid = (string) ($row['playlist_id'] ?? '');
                if ($pid) {
                    $counts[$pid] = ($counts[$pid] ?? 0) + 1;
                }
            }
            return $counts;
        } catch (\Throwable $e) {
            Log::error('getSongLikeCounts error: ' . $e->getMessage());
            return [];
        }
    }

    /* ============================================================
     * HTTP WRAPPER
     * ============================================================ */

    protected function get(array $params): array
    {
        try {
            $res = Http::withOptions(['allow_redirects' => true])
                ->timeout(30)
                ->get($this->url, $params);

            return $res->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('GSheet GET error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function post(array $payload): array
    {
        try {
            $res = Http::withOptions(['allow_redirects' => true])
                ->timeout(30)
                ->post($this->url, $payload);

            return $res->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('GSheet POST error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}