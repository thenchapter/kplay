@extends('layouts.app')

@php
    use App\Support\Sx;

    $idol  = $idol ?? [];
    $n     = Sx::get($idol, 'nama', 'name') ?? 'Idol';
    $gname = $gname ?? Sx::groupName($idol) ?? '';
    $mates = $mates ?? [];

    $birthRaw = Sx::get($idol, 'lahir', 'birthday', 'tanggal_lahir');
    $birthStr = $birthRaw ? Sx::date($birthRaw) : null;

    // Cek apakah idol ini sudah di-bias user
    $user      = session('user') ?? [];
    $biasIds   = $user['bias_ids'] ?? [];
    $isBiased  = in_array($idol['id'] ?? null, $biasIds);
    $idolId    = $idol['id'] ?? 0;
@endphp

@section('title', $n)
@section('heading', $n)
@section('sub', $gname)

@section('content')

<div class="grid md:grid-cols-3 gap-8">

    {{-- FOTO + TOMBOL BIAS --}}
    <div class="space-y-4">
        <img
            src="{{ Sx::get($idol, 'foto', 'image', 'avatar') ?: Sx::ph($n) }}"
            alt="{{ $n }}"
            referrerpolicy="no-referrer"
            onerror="this.onerror=null;this.src='{{ Sx::ph($n) }}'"
            class="w-full rounded-3xl border border-border object-cover aspect-[4/5]"
        >

        {{-- ===== TOMBOL JADIKAN BIAS ===== --}}
        @if($isBiased)
            <form action="{{ route('profile.bias.remove') }}" method="POST">
                @csrf
                <input type="hidden" name="idol_id" value="{{ $idolId }}">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold text-red-400 border border-red-500/30 hover:bg-red-500/10 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                    Hapus dari Bias
                </button>
            </form>
        @else
            <form action="{{ route('profile.bias.add') }}" method="POST">
                @csrf
                <input type="hidden" name="idol_id" value="{{ $idolId }}">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-[#d946ef] to-[#06b6d4] hover:opacity-90 transition-all shadow-lg shadow-[#d946ef]/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                    Jadikan Bias
                </button>
            </form>
        @endif
    </div>

    {{-- DETAIL IDOL --}}
    <div class="md:col-span-2 space-y-6">

        <div class="bg-surface border border-border rounded-2xl p-6 grid sm:grid-cols-2 gap-5 text-sm">
            @php
                $info = [
                    'Grup'         => $gname,
                    'Posisi'       => Sx::get($idol, 'posisi', 'position'),
                    'Followers'    => Sx::get($idol, 'followers'),
                    'Tanggal Lahir'=> $birthStr,
                    'Negara'       => Sx::get($idol, 'negara', 'country'),
                ];
            @endphp

            @foreach($info as $k => $v)
                @if($v)
                    <div>
                        <p class="text-xs uppercase tracking-wider text-zinc-500">{{ $k }}</p>
                        <p class="font-semibold text-base">{{ $v }}</p>
                    </div>
                @endif
            @endforeach
        </div>

        @php $bio = Sx::get($idol, 'bio', 'deskripsi', 'about'); @endphp
        @if($bio)
            <div class="bg-surface border border-border rounded-2xl p-6 text-zinc-300 leading-relaxed">
                {{ $bio }}
            </div>
        @endif

        @if(count($mates))
            <div>
                <h2 class="font-display font-bold text-xl mb-3">Member {{ $gname ?: 'Grup' }} lainnya</h2>
                <div class="flex flex-wrap gap-4">
                    @foreach($mates as $m)
                        @php
                            $mn  = Sx::get($m, 'nama', 'name') ?? 'Member';
                            $mId = $m['id'] ?? 0;
                        @endphp
                        <a href="{{ route('idols.show', $mId) }}" class="text-center w-20 group">
                            <img
                                src="{{ Sx::get($m, 'foto', 'image') ?: Sx::ph($mn) }}"
                                alt="{{ $mn }}"
                                referrerpolicy="no-referrer"
                                onerror="this.onerror=null;this.src='{{ Sx::ph($mn) }}'"
                                class="w-16 h-16 mx-auto rounded-full object-cover ring-2 ring-border group-hover:ring-accent transition"
                            >
                            <p class="text-xs mt-1 truncate">{{ $mn }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('idols.index') }}" class="inline-block text-sm font-bold text-accent hover:text-white transition">
            ← Semua idol
        </a>

    </div>

</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
    <div id="flashMsg" class="fixed bottom-6 right-6 z-[999] bg-emerald-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        {{ session('success') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashMsg')?.remove(), 3000);</script>
@endif

@if(session('error'))
    <div id="flashErr" class="fixed bottom-6 right-6 z-[999] bg-red-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        {{ session('error') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashErr')?.remove(), 3000);</script>
@endif

@endsection