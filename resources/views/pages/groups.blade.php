@extends('layouts.app')

@php
    use App\Support\Sx;

    $groups         = $groups ?? [];
    $idols          = $idols ?? [];
    $followedIds    = $followedIds ?? [];
    $isFollowingMap = array_flip(array_map('strval', $followedIds));
@endphp

@section('title', 'Groups')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap');

    .font-display { font-family: 'Space Grotesk', 'Inter', sans-serif !important; letter-spacing: -0.03em; }
    .font-body    { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif !important; }
    .font-mono    { font-family: 'JetBrains Mono', monospace !important; }

    .group-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: transform .5s cubic-bezier(.25,.46,.45,.94);
    }
    .group-card:hover { transform: translateY(-8px); }

    .avatar-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .orbit-ring {
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        background: conic-gradient(from 0deg, #d946ef, transparent 30%, #06b6d4, transparent 60%, #8b5cf6, transparent 90%, #d946ef);
        animation: orbitSpin 6s linear infinite;
        opacity: 0;
        transition: opacity .5s;
    }
    .group-card:hover .orbit-ring { opacity: 1; }
    @keyframes orbitSpin { to { transform: rotate(360deg); } }

    .avatar-glow {
        position: absolute;
        inset: -20px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(217,70,239,.4) 0%, transparent 70%);
        animation: glowPulse 3s ease-in-out infinite;
        z-index: -1;
    }
    @keyframes glowPulse { 0%,100%{opacity:.4;transform:scale(1);} 50%{opacity:.7;transform:scale(1.1);} }

    .avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        box-shadow: 0 0 0 4px rgba(10,10,15,1), 0 0 0 5px rgba(217,70,239,.3);
        transition: box-shadow .5s, transform .5s;
        z-index: 1;
    }
    .group-card:hover .avatar-img {
        box-shadow: 0 0 0 4px rgba(10,10,15,1), 0 0 0 5px rgba(6,182,212,.8);
        transform: scale(1.05);
    }
    .avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .8s cubic-bezier(.25,.46,.45,.94);
    }
    .group-card:hover .avatar-img img { transform: scale(1.15); }

    .group-name {
        margin-top: 16px;
        font-weight: 700;
        font-size: 17px;
        letter-spacing: -0.03em;
        color: #fff;
        transition: color .3s;
        line-height: 1.2;
    }
    .group-card:hover .group-name { color: #d946ef; }

    .group-meta {
        font-size: 11px;
        color: #a1a1aa;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .meta-agency { color: #e4e4e7; font-weight: 600; }
    .meta-year   { color: #06b6d4; font-weight: 600; }
    .meta-dot    { color: #d946ef; font-weight: 700; }

    /* ============================================================
       FOLLOW BUTTON — PASTI MUNCUL
       ============================================================ */
    .follow-btn {
        margin-top: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 8px 18px;
        border-radius: 999px;
        transition: all .3s cubic-bezier(.25,.46,.45,.94);
        cursor: pointer;
        border: 1px solid transparent;
        min-width: 110px;
        text-decoration: none;
    }

    .follow-btn.follow {
        background: linear-gradient(135deg, #d946ef, #8b5cf6);
        color: #fff;
        box-shadow: 0 4px 15px rgba(217,70,239,.4);
    }
    .follow-btn.follow:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(217,70,239,.6);
    }

    .follow-btn.following {
        background: transparent;
        color: #a1a1aa;
        border-color: rgba(255,255,255,.2);
    }
    .follow-btn.following:hover {
        background: rgba(239,68,68,.1);
        color: #f87171;
        border-color: rgba(239,68,68,.4);
    }

    .stagger { opacity: 0; animation: fadeInUp .8s cubic-bezier(.25,.46,.45,.94) forwards; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px) scale(.9); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .float-soft { animation: floatSoft 4s ease-in-out infinite; }
    @keyframes floatSoft { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-6px);} }
</style>

<div class="max-w-7xl mx-auto">

    {{-- HERO --}}
    <div class="relative mb-12 pt-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#d946ef] animate-pulse"></span>
                    <span class="text-xs font-bold tracking-[4px] text-[#d946ef] uppercase font-mono">K-PLAY Groups</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-display font-black text-white leading-[1.05]">
                    Explore<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#d946ef] via-[#8b5cf6] to-[#06b6d4]">Groups.</span>
                </h1>
                <p class="text-sm text-zinc-400 mt-4 max-w-lg leading-relaxed font-body">
                    Jelajahi berbagai grup K-Pop, kenali member, dan follow favoritmu.
                </p>
            </div>
            <div class="flex gap-8">
                <div class="text-center float-soft">
                    <p class="text-5xl font-display font-black text-white">{{ count($groups) }}</p>
                    <p class="text-[10px] text-zinc-500 uppercase tracking-wider mt-2 font-mono">Groups</p>
                </div>
                <div class="text-center float-soft" style="animation-delay:.5s">
                    <p class="text-5xl font-display font-black text-white">{{ count($idols) }}</p>
                    <p class="text-[10px] text-zinc-500 uppercase tracking-wider mt-2 font-mono">Idols</p>
                </div>
            </div>
        </div>
    </div>

    {{-- GRID --}}
    @if(count($groups) > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-14 pb-20">

            @foreach($groups as $index => $g)
                @php
                    $n      = Sx::get($g, 'nama', 'name') ?? 'Grup';
                    $gId    = $g['id'] ?? null;
                    $logo   = Sx::get($g, 'logo', 'foto', 'image');
                    $agensi = Sx::get($g, 'agensi', 'agency');
                    $debut  = Sx::get($g, 'debut');

                    $isFollowing = isset($isFollowingMap[(string) $gId]);

                    $mem = array_filter($idols, function ($i) use ($n, $gId) {
                        $iGroupName = strtolower(Sx::groupName($i) ?? '');
                        $matchByName = $iGroupName === strtolower($n);
                        $iGroupId = $i['group_id'] ?? null;
                        $matchById = ($iGroupId !== null && $gId !== null && $iGroupId == $gId);
                        return $matchByName || $matchById;
                    });
                @endphp

                <div class="group-card stagger"
                     style="animation-delay: {{ min($index * 0.06, 1) }}s;">

                    {{-- AVATAR --}}
                    <div class="avatar-wrapper">
                        <div class="avatar-glow"></div>
                        <div class="orbit-ring"></div>

                        <div class="avatar-img">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $n }}" loading="lazy" referrerpolicy="no-referrer"
                                     onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-logo').style.display='flex';">
                            @endif
                            <div class="fallback-logo {{ $logo ? 'hidden' : 'flex' }} absolute inset-0 items-center justify-center bg-gradient-to-br from-[#1a1228] to-[#0f1a1f]">
                                <span class="text-3xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-br from-[#d946ef] to-[#06b6d4]">
                                    {{ strtoupper(substr($n, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <h3 class="group-name font-display">{{ $n }}</h3>

                    <p class="group-meta font-mono">
                        @if($agensi)<span class="meta-agency">{{ $agensi }}</span>@endif
                        @if($agensi && $debut)<span class="meta-dot">·</span>@endif
                        @if($debut)<span class="meta-year">{{ $debut }}</span>@endif
                    </p>

                    {{-- ============================================================
                         TOMBOL FOLLOW / UNFOLLOW
                         ============================================================ --}}
                    @if(session('user'))
                        <form action="{{ $isFollowing ? route('groups.unfollow') : route('groups.follow') }}"
                              method="POST"
                              class="mt-3">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $gId }}">
                            <button type="submit"
                                    class="follow-btn {{ $isFollowing ? 'following' : 'follow' }}">
                                @if($isFollowing)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                    Following
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v14M5 12h14"/>
                                    </svg>
                                    Follow
                                @endif
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="follow-btn follow mt-3">
                            Login
                        </a>
                    @endif

                    {{-- MEMBER AVATARS --}}
                    @if(count($mem) > 0)
                        <div class="flex items-center justify-center gap-1.5 mt-3">
                            <div class="flex -space-x-1.5">
                                @foreach(array_slice($mem, 0, 4) as $m)
                                    @php
                                        $mn  = Sx::get($m, 'nama', 'name') ?? 'M';
                                        $mId = $m['id'] ?? 0;
                                        $mF  = Sx::get($m, 'foto', 'image') ?: Sx::ph($mn);
                                    @endphp
                                    <a href="{{ route('idols.show', $mId) }}" title="{{ $mn }}"
                                       class="w-6 h-6 rounded-full overflow-hidden ring-2 ring-[#0a0a0f] hover:scale-125 transition-transform">
                                        <img src="{{ $mF }}" alt="{{ $mn }}" loading="lazy" referrerpolicy="no-referrer"
                                             onerror="this.onerror=null;this.src='{{ Sx::ph($mn) }}'"
                                             class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                                @if(count($mem) > 4)
                                    <div class="w-6 h-6 rounded-full ring-2 ring-[#0a0a0f] bg-gradient-to-br from-[#d946ef] to-[#06b6d4] flex items-center justify-center text-[8px] font-mono font-bold text-white">
                                        +{{ count($mem) - 4 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach

        </div>
    @else
        <div class="text-center py-24">
            <h3 class="text-lg font-bold text-white mb-2">Belum Ada Grup</h3>
            <p class="text-sm text-zinc-500">Data grup K-Pop belum tersedia saat ini.</p>
        </div>
    @endif

</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
    <div id="flashMsg" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-emerald-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        {{ session('success') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashMsg')?.remove(), 3000);</script>
@endif

@if(session('error'))
    <div id="flashErr" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-red-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        {{ session('error') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashErr')?.remove(), 3000);</script>
@endif

@endsection