@extends('layouts.app')

@php
    $u       = session('user') ?? [];
    $uNama   = $u['nama'] ?? $u['name'] ?? 'User';
    
    // Perbaiki format username agar tidak dobel '@'
    $rawUn   = $u['username'] ?? '';
    $uUn     = $rawUn ? ltrim($rawUn, '@') : strtolower(str_replace(' ', '', $uNama));
    
    $uEmail  = $u['email'] ?? '';
    $uBio    = $u['bio'] ?? 'K-Pop enthusiast. Here to vibe with fellow fans.';
    $uAvatar = $u['avatar'] ?? null;

    // Avatar handling (Mendukung URL luar DAN file lokal dari storage)
    if ($uAvatar) {
        if (str_starts_with($uAvatar, 'http')) {
            $avatarUrl = $uAvatar;
        } else {
            $avatarUrl = asset('storage/' . $uAvatar);
        }
    } else {
        $avatarUrl = 'https://ui-avatars.com/api/?size=300&background=d946ef&color=fff&name=' . urlencode($uNama);
    }

    $stats = [
        ['label' => 'Following', 'value' => $u['following'] ?? 0],
        ['label' => 'Followers', 'value' => $u['followers'] ?? 0],
        ['label' => 'Likes',     'value' => $u['likes'] ?? 0],
    ];

    $tabs = [
        ['label' => 'Bias',       'route' => 'profile.bias',          'url' => '/profile/bias'],
        ['label' => 'Grup',       'route' => 'profile.groups',        'url' => '/profile/groups'],
        ['label' => 'Playlist',   'route' => 'profile.playlist',      'url' => '/profile/playlist'],
        ['label' => 'Notifikasi', 'route' => 'profile.notifications', 'url' => '/profile/notifications'],
    ];
@endphp

@section('title', $uNama)

@section('content')

<div class="max-w-2xl mx-auto -mt-6 animate-fade-up">

    {{-- ============================================================ --}}
    {{-- HEADER PROFIL (ala TikTok) --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col items-center text-center pt-8 pb-5">

        {{-- AVATAR --}}
        <div class="relative group mb-4">
            <div class="absolute -inset-1 bg-gradient-to-tr from-[#d946ef] to-[#06b6d4] rounded-full blur opacity-40 group-hover:opacity-80 transition-opacity"></div>

            <img src="{{ $avatarUrl }}"
                 alt="{{ $uNama }}"
                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?size=300&background=d946ef&color=fff&name={{ urlencode($uNama) }}'"
                 class="relative w-24 h-24 rounded-full object-cover ring-4 ring-[#0a0a0f]">
            
            {{-- Tombol Ganti Foto --}}
            <button type="button" onclick="document.getElementById('photoModal').classList.remove('hidden')"
                   class="absolute inset-0 rounded-full bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[10px] font-bold z-10 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                Ganti
            </button>
        </div>

        {{-- USERNAME --}}
        <h1 class="text-xl font-bold text-white">@ {{ $uUn }}</h1>

        {{-- NAMA --}}
        <p class="text-sm text-zinc-400 mt-0.5">{{ $uNama }}</p>

        {{-- BIO --}}
        @if($uBio)
            <p class="text-sm text-zinc-300 mt-3 max-w-sm leading-relaxed">{{ $uBio }}</p>
        @endif

    </div>

    {{-- ============================================================ --}}
    {{-- STATS HORIZONTAL --}}
    {{-- ============================================================ --}}
    <div class="flex items-center justify-center gap-10 py-4 border-y border-[#1f1f27]">
        @foreach($stats as $s)
            <div class="text-center">
                <p class="text-lg font-bold text-white">{{ $s['value'] }}</p>
                <p class="text-xs text-zinc-500">{{ $s['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- TOMBOL EDIT & LOGOUT --}}
    {{-- ============================================================ --}}
    <div class="px-4 py-4">
        <div class="flex gap-2">
            <button class="flex-1 text-center py-2.5 rounded-lg bg-white text-black text-sm font-bold opacity-50 cursor-not-allowed" disabled title="Fitur edit profil segera hadir">
                Edit Profil
            </button>

            <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit"
                        class="w-11 h-11 rounded-lg bg-[#121218] border border-[#1f1f27] flex items-center justify-center text-red-400 hover:bg-red-500/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TAB MENU --}}
    {{-- ============================================================ --}}
    <div class="sticky top-16 z-40 bg-[#0a0a0f]/95 backdrop-blur border-b border-[#1f1f27]">
        <div class="flex overflow-x-auto scrollbar-hide">

            <button class="flex-1 min-w-[90px] py-3.5 text-sm font-semibold text-white border-b-2 border-white">
                Profil
            </button>

            @foreach($tabs as $tab)
                @if(\Illuminate\Support\Facades\Route::has($tab['route']))
                    <a href="{{ route($tab['route']) }}"
                       class="flex-1 min-w-[90px] py-3.5 text-sm font-semibold text-zinc-500 hover:text-white border-b-2 border-transparent hover:border-zinc-600 transition text-center">
                        {{ $tab['label'] }}
                    </a>
                @endif
            @endforeach

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- INFO LIST --}}
    {{-- ============================================================ --}}
    <div class="px-4 py-4">
        <div class="flex items-start gap-4 py-4 border-b border-[#1f1f27]">
            <span class="text-zinc-500 mt-0.5 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-zinc-500">Email</p>
                <p class="text-sm text-white truncate">{{ $uEmail ?: 'Belum diisi' }}</p>
            </div>
        </div>

        <div class="flex items-start gap-4 py-4 border-b border-[#1f1f27]">
            <span class="text-zinc-500 mt-0.5 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-zinc-500">Username</p>
                <p class="text-sm text-white truncate">{{ '@' . $uUn }}</p>
            </div>
        </div>

        <div class="flex items-start gap-4 py-4 border-b border-[#1f1f27]">
            <span class="text-zinc-500 mt-0.5 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-zinc-500">Bio</p>
                <p class="text-sm text-white leading-relaxed">{{ $uBio ?: 'Belum diisi' }}</p>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- MODAL GANTI FOTO (Upload File) --}}
{{-- ============================================================ --}}
<div id="photoModal" class="hidden fixed inset-0 z-[999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#121218] border border-[#1f1f27] rounded-2xl w-full max-w-md p-6 relative shadow-2xl">
        <button onclick="document.getElementById('photoModal').classList.add('hidden')" class="absolute top-4 right-4 text-zinc-400 hover:text-white text-xl">✕</button>
        <h3 class="text-lg font-bold text-white mb-4">Ganti Foto Profil</h3>
        
        <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="block text-sm text-zinc-400 mb-2">Pilih Foto dari Perangkat</label>
            <input type="file" name="photo" accept="image/*" required
                   class="w-full bg-[#0a0a0f] border border-[#1f1f27] rounded-xl px-4 py-3 text-sm text-white focus:border-[#d946ef] focus:outline-none mb-4
                          file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#d946ef]/20 file:text-[#d946ef] hover:file:bg-[#d946ef]/30">
            
            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#d946ef] to-[#06b6d4] text-white font-bold hover:scale-[1.02] transition">
                Upload & Simpan
            </button>
        </form>
    </div>
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
    <div id="flashMsg" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-emerald-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl flex items-center gap-2 animate-fade-up">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        {{ session('success') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashMsg')?.remove(), 3000);</script>
@endif

@if(session('error'))
    <div id="flashErr" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-red-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl flex items-center gap-2 animate-fade-up">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        {{ session('error') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashErr')?.remove(), 3000);</script>
@endif

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection