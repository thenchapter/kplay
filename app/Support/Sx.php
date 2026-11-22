<?php
namespace App\Support;

use App\Services\GoogleSheetService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/** Helper membaca/menulis Google Sheet dengan cache + toleran terhadap perbedaan nama kolom. */
class Sx
{
    // Kelompok nama kolom yang dianggap sama (dipakai saat menyimpan data baru)
    const SYN = [
        ['isi','konten','content','body','caption','deskripsi','komentar','pesan'],
        ['judul','title'], ['user_id','id_user','author_id'],
        ['gambar','foto','image','img','url','link'], ['created_at','tanggal','date','waktu'],
    ];

    public static function all(string $sheet): array
    {
        return Cache::remember("sx.$sheet", 60, fn() => app(GoogleSheetService::class)->getAll($sheet) ?? []);
    }
    public static function forget(string $sheet): void { Cache::forget("sx.$sheet"); }

    /** Ambil nilai pertama yang terisi dari beberapa kemungkinan nama kolom. */
    public static function get($row, ...$keys)
    {
        foreach ($keys as $k) if (isset($row[$k]) && $row[$k] !== '') return $row[$k];
        return null;
    }
    public static function find(string $sheet, $id)
    {
        foreach (self::all($sheet) as $r) if ((string)($r['id'] ?? '') === (string)$id) return $r;
        return null;
    }
    public static function user($id) { return $id === null ? null : self::find('users', $id); }

    public static function groupName($idol): string
    {
        $n = self::get($idol, 'grup', 'group', 'nama_grup');
        if ($n) return $n;
        $g = self::find('groups', self::get($idol, 'group_id', 'grup_id'));
        return $g ? (string) self::get($g, 'nama', 'name') : '';
    }
    public static function ts($v): int { try { return $v ? Carbon::parse($v)->timestamp : 0; } catch (\Throwable $e) { return 0; } }
    public static function date($v, $f = 'd M Y'): string { try { return $v ? Carbon::parse($v)->translatedFormat($f) : ''; } catch (\Throwable $e) { return (string)$v; } }
    public static function ago($v): string { try { return $v ? Carbon::parse($v)->diffForHumans() : ''; } catch (\Throwable $e) { return (string)$v; } }
    public static function ph($name, $bg = 'd946ef'): string { return 'https://ui-avatars.com/api/?size=500&background=' . $bg . '&color=fff&name=' . urlencode($name ?: 'K'); }

    /** Simpan baris baru; kunci otomatis disesuaikan dengan header sheet (jika sudah ada isinya). */
    public static function insert(string $sheet, array $data)
    {
        $rows = self::all($sheet);
        $cols = $rows ? array_keys($rows[0]) : [];
        $out = [];
        foreach ($data as $k => $v) {
            $t = $k;
            if ($cols && !in_array($k, $cols)) {
                foreach (self::SYN as $g) {
                    if (in_array($k, $g)) { foreach ($g as $alt) if (in_array($alt, $cols)) { $t = $alt; break 2; } }
                }
            }
            $out[$t] = $v;
        }
        $id = app(GoogleSheetService::class)->insert($sheet, $out);
        self::forget($sheet);
        return $id;
    }
}
