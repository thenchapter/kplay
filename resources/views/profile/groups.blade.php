@extends('layouts.app')

@php
    use App\Support\Sx;

    $groups      = $groups ?? [];
    $followedIds = $followedIds ?? [];
    $total       = count($groups);
    $isFollowingMap = array_flip(array_map('strval', $followedIds));

    $tabs = [
        ['label' => 'Profil',     'route' => 'profile.index',         'url' => '/profile'],
        ['label' => 'Bias',       'route' => 'profile.bias',          'url' => '/profile/bias'],
        ['label' => 'Grup',       'route' => 'profile.groups',        'url' => '/profile/groups'],
        ['label' => 'Playlist',   'route' => 'profile.playlist',      'url' => '/profile/playlist'],
        ['label' => 'Notifikasi', 'route' => 'profile.notifications', 'url' => '/profile/notifications'],
    ];
@endphp

@section('title', 'Grup Favorit')

@section('content')

<div class="max-w-2xl mx-auto -mt-6">

    {{-- TAB MENU --}}
    <div class="sticky top-16 z-40 bg-[#0a0a0f]/95 backdrop-blur border-b border-[#1f1f27]">
        <div class="flex overflow-x-auto scrollbar-hide">
            @foreach($tabs as $tab)
                @php
                    $href     = \Illuminate\Support\Facades\Route::has($tab['route']) ? route($tab['route']) : url($tab['url']);
                    $isActive = $tab['route'] === 'profile.groups';
                @endphp
                <a href="{{ $href }}"
                   class="flex-1 min-w-[90px] py-3.5 text-sm font-semibold border-b-2 transition text-center
                          {{ $isActive
                              ? 'text-white border-white'
                              : 'text-zinc-500 hover:text-white border-transparent hover:border-zinc-600' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- INFO --}}
    <div class="px-4 py-3 flex items-center justify-between">
        <p class="text-xs text-zinc-500">
            <span class="text-white font-bold">{{ $total }}</span> grup yang kamu ikuti
        </p>
        <a href="{{ route('groups.index') }}"
           class="text-[11px] font-bold text-[#d946ef] hover:text-white transition">
            Jelajahi →
        </a>
    </div>

    {{-- GRID GRUP (ala TikTok, tanpa border) --}}
    @if($total > 0)

        <div class="grid grid-cols-3 gap-0.5 px-0.5">

            @foreach($groups as $g)
                @php
                    $n      = Sx::get($g, 'nama', 'name') ?? 'Grup';
                    $gId    = $g['id'] ?? null;
                    $logo   = Sx::get($g, 'logo', 'foto', 'image');
                    $agensi = Sx::get($g, 'agensi', 'agency');
                    $debut  = Sx::get($g, 'debut');

                    $isFollowing = isset($isFollowingMap[(string) $gId]);
                @endphp

                <div class="group relative aspect-square overflow-hidden bg-[#121218]">

                    {{-- FOTO --}}
                    <a href="{{ route('groups.index') }}" class="absolute inset-0">
                        @if($logo)
                            <img src="{{ $logo }}"
                                 alt="{{ $n }}"
                                 loading="lazy"
                                 referrerpolicy="no-referrer"
                                 onerror="this.onerror=null;this.src='{{ Sx::ph($n, '06b6d4') }}'"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#1a1228] to-[#0f1a1f]">
                                <span class="text-3xl font-display font-black text-transparent bg-clip-text bg-gradient-to-br from-[#d946ef] to-[#06b6d4]">
                                    {{ strtoupper(substr($n, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    </a>

                    {{-- TOMBOL UNFOLLOW (muncul saat hover) --}}
                    <form action="{{ route('groups.unfollow') }}" method="POST" class="absolute top-1.5 right-1.5 z-20">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $gId }}">
                        <button type="submit"
                                title="Unfollow"
                                class="w-7 h-7 rounded-full bg-black/70 backdrop-blur border border-white/10 flex items-center justify-center text-red-400 hover:text-white hover:bg-red-500/80 transition-all opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </form>

                    {{-- NAMA & META --}}
                    <div class="absolute bottom-0 left-0 right-0 p-2 pointer-events-none">
                        @if($agensi)
                            <p class="text-[9px] font-bold uppercase tracking-wider text-[#d946ef] truncate">
                                {{ $agensi }}@if($debut) · {{ $debut }}@endif
                            </p>
                        @endif
                        <p class="text-xs font-bold text-white truncate">{{ $n }}</p>
                    </div>

                </div>
            @endforeach

        </div>

    @else

        {{-- EMPTY STATE --}}
        <div class="text-center py-24 px-4">
            <div class="w-16 h-16 rounded-full bg-[#121218] border border-[#1f1f27] flex items-center justify-center mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5" class="text-zinc-500">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Belum Ada Grup Favorit</h3>
            <p class="text-sm text-zinc-500 mb-5 max-w-xs mx-auto">
                Jelajahi daftar grup dan follow grup favoritmu!
            </p>
            <a href="{{ route('groups.index') }}"
               class="inline-flex items-center gap-2 bg-white text-black text-sm font-bold px-5 py-2.5 rounded-lg hover:bg-gray-200 transition">
                Jelajahi Groups
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
        </div>

    @endif

</div>

{{-- FLASH --}}
@if(session('success'))
    <div id="flashMsg" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-emerald-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl">
        {{ session('success') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashMsg')?.remove(), 3000);</script>
@endif

@if(session('error'))
    <div id="flashErr" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-red-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl">
        {{ session('error') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashErr')?.remove(), 3000);</script>
@endif

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection