<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;

class IdolController extends Controller
{
    protected GoogleSheetService $gs;

    public function __construct(GoogleSheetService $gs)
    {
        $this->gs = $gs;
    }

    /**
     * GET /idols → idols.index
     */
    public function index()
    {
        $idols  = $this->gs->getAll('idols')  ?: [];
        $groups = $this->gs->getAll('groups') ?: [];

        return view('pages.idols', compact('idols', 'groups'));
    }

    /**
     * GET /idols/{id} → idols.show
     */
    public function show($id)
    {
        $idol = $this->gs->getById('idols', $id);

        if (!$idol) {
            abort(404, 'Idol tidak ditemukan');
        }

        // Nama grup: prioritaskan field group_name, fallback lookup by group_id
        $gname = $idol['group_name'] ?? '';
        $gid   = $idol['group_id']   ?? null;

        if ($gname === '' && $gid !== null) {
            $grp   = $this->gs->getById('groups', $gid);
            $gname = $grp['nama'] ?? $grp['name'] ?? '';
        }

        // Member lain di grup yang sama
        $mates = collect($this->gs->getAll('idols') ?: [])
            ->filter(function ($i) use ($idol, $gname, $gid) {
                $isSelf = (string) ($i['id'] ?? '') === (string) ($idol['id'] ?? '');

                $sameById   = $gid !== null
                    && isset($i['group_id'])
                    && (string) $i['group_id'] === (string) $gid;

                $sameByName = $gname !== ''
                    && isset($i['group_name'])
                    && $i['group_name'] === $gname;

                return !$isSelf && ($sameById || $sameByName);
            })
            ->values()
            ->all();

        return view('pages.idol-show', compact('idol', 'gname', 'mates'));
    }
}