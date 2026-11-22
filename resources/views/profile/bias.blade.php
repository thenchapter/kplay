@extends('layouts.app')

@php
    use App\Support\Sx;

    // Pastikan variabel selalu berupa array agar count() dan foreach tidak error
    $biases  = $biases ?? [];
    $biasIds = $biasIds ?? [];
    $total   = count($biases);

    $tabs = [
        ['label' => 'Profil',     'route' => 'profile.index',         'url' => '/profile'],
        ['label' => 'Bias',       'route' => 'profile.bias',          'url' => '/profile/bias'],
        ['label' => 'Grup',       'route' => 'profile.groups',        'url' => '/profile/groups'],
        ['label' => 'Playlist',   'route' => 'profile.playlist',      'url' => '/profile/playlist'],
        ['label' => 'Notifikasi', 'route' => 'profile.notifications', 'url' => '/profile/notifications'],
    ];
@endphp

@section('title', 'My Bias')

@section('content')

<div class="max-w-2xl mx-auto -mt-6">

    {{-- TAB MENU --}}
    <div class="sticky top-16 z-40 bg-[#0a0a0f]/95 backdrop-blur border-b border-[#1f1f27]">
        <div class="flex overflow-x-auto scrollbar-hide">
            @foreach($tabs as $tab)
                @php
                    $href     = \Illuminate\Support\Facades\Route::has($tab['route']) ? route($tab['route']) : url($tab['url']);
                    $isActive = $tab['route'] === 'profile.bias';
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
            <span class="text-white font-bold">{{ $total }}</span> idol favoritmu
        </p>
    </div>

    {{-- GRID FOTO (ala TikTok, tanpa border) --}}
    @if($total > 0)

        <div class="grid grid-cols-3 gap-0.5 px-0.5">

            @foreach($biases as $b)
                @php
                    // Ambil nama idol dengan fallback
                    $n = Sx::get($b, 'nama', 'name') ?? 'Idol';
                    
                    // PERBAIKAN: Panggil Sx::groupName dengan aman. 
                    // Jika method tidak ada atau return null, gunakan string kosong.
                    $grupp = method_exists(Sx::class, 'groupName') ? (Sx::groupName($b) ?? '') : '';
                    if (empty($grupp)) {
                        $grupp = Sx::get($b, 'grup', 'group', 'nama_grup') ?? '';
                    }

                    // Ambil foto dengan fallback placeholder
                    $foto = Sx::get($b, 'foto', 'image', 'avatar') ?: Sx::ph($n);
                    
                    // Pastikan ID aman (string atau angka)
                    $id = $b['id'] ?? 0;
                @endphp

                <div class="group relative aspect-square overflow-hidden bg-[#121218]">

                    {{-- FOTO --}}
                    <a href="{{ route('idols.show', $id) }}" class="absolute inset-0">
                        <img src="{{ $foto }}"
                             alt="{{ $n }}"
                             loading="lazy"
                             referrerpolicy="no-referrer"
                             onerror="this.onerror=null;this.src='{{ Sx::ph($n) }}'"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    </a>

                    {{-- TOMBOL HAPUS --}}
                    {{-- Pastikan route 'profile.bias.remove' ada di web.php, jika tidak ini akan error --}}
                    @if(Route::has('profile.bias.remove'))
                        <form action="{{ route('profile.bias.remove') }}" method="POST" class="absolute top-1.5 right-1.5 z-20">
                            @csrf
                            <input type="hidden" name="idol_id" value="{{ $id }}">
                            <button type="submit"
                                    title="Hapus"
                                    class="w-7 h-7 rounded-full bg-black/70 backdrop-blur border border-white/10 flex items-center justify-center text-red-400 hover:text-white hover:bg-red-500/80 transition-all opacity-0 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                </svg>
                            </button>
                        </form>
                    @endif

                    {{-- NAMA --}}
                    <div class="absolute bottom-0 left-0 right-0 p-2 pointer-events-none">
                        @if($grupp)
                            <p class="text-[9px] font-bold uppercase tracking-wider text-[#d946ef] truncate">{{ $grupp }}</p>
                        @endif
                        <p class="text-xs font-bold text-white truncate">{{ $n }}</p>
                    </div>

                </div>
            @endforeach

        </div>

    @else

        <div class="text-center py-24 px-4">
            <div class="w-16 h-16 rounded-full bg-[#121218] border border-[#1f1f27] flex items-center justify-center mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5" class="text-zinc-500">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Belum Ada Bias</h3>
            <p class="text-sm text-zinc-500 mb-5 max-w-xs mx-auto">Jelajahi daftar idol dan tambahkan bias-mu!</p>
            <a href="{{ route('idols.index') }}"
               class="inline-flex items-center gap-2 bg-white text-black text-sm font-bold px-5 py-2.5 rounded-lg hover:bg-gray-200 transition">
                Jelajahi Idols
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

    @endif

</div>

{{-- FLASH MESSAGES --}}
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