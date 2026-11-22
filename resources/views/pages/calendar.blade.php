@extends('layouts.app')
@php use App\Support\Sx; @endphp

@section('title', 'Kalender Comeback')
@section('heading', 'Kalender Comeback')
@section('sub', 'Jadwal rilis dan comeback idol favoritmu')

@section('content')
<!-- Tombol Navigasi Tab -->
<div class="flex gap-3 mb-8">
    <a href="{{ route('news.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-surface border border-border text-zinc-400 hover:text-white transition">📰 Berita</a>
    <a href="{{ route('news.calendar') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-accent to-purple-600 text-white shadow-lg">📅 Kalender Comeback</a>
</div>

@foreach([['Akan Datang', $upcoming, true], ['Sudah Rilis', $past, false]] as [$label, $list, $up])
    <h2 class="font-display font-bold text-xl mb-4 mt-8 flex items-center gap-2">
        {{ $label }} 
        <span class="text-zinc-500 text-base font-medium bg-surface px-2.5 py-0.5 rounded-lg border border-border">· {{ count($list) }}</span>
    </h2>
    
    <div class="grid md:grid-cols-2 gap-4">
        @forelse($list as $c)
            @php 
                $d = $c['_t'] ? \Carbon\Carbon::createFromTimestamp($c['_t']) : null; 
                $gr = Sx::get($c, 'grup', 'artis', 'artist', 'group'); 
                $jd = Sx::get($c, 'judul', 'title', 'album'); 
            @endphp
            
            <div class="flex gap-4 bg-surface border {{ $up ? 'border-accent/40 shadow-[0_0_15px_rgba(var(--accent-rgb),0.1)]' : 'border-border opacity-70' }} rounded-2xl p-4 card-hover">
                <!-- Kotak Tanggal -->
                <div class="w-16 shrink-0 text-center rounded-xl bg-gradient-to-br from-accent/25 to-cyan/20 py-2 flex flex-col justify-center">
                    <p class="text-2xl font-display font-extrabold leading-none">{{ $d ? $d->format('d') : '-' }}</p>
                    <p class="text-[11px] uppercase text-zinc-300 mt-1">{{ $d ? $d->translatedFormat('M Y') : '' }}</p>
                </div>
                
                <!-- Info Teks -->
                <div class="min-w-0 flex-1 flex flex-col justify-center">
                    <p class="text-xs font-bold text-accent uppercase tracking-wider">{{ $gr }}</p>
                    <h3 class="font-display font-bold text-lg truncate text-white">{{ $jd }}</h3>
                    <p class="text-xs text-zinc-400">
                        {{ Sx::get($c, 'type', 'tipe', 'jenis') }}
                        @if($up && $d)
                            · <span class="{{ $d->isToday() ? 'text-green-400 font-bold' : '' }}">{{ $d->isToday() ? 'Hari ini!' : $d->diffForHumans() }}</span>
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <div class="col-span-full py-6 text-center border border-dashed border-border rounded-2xl bg-surface/50">
                <p class="text-zinc-500 text-sm">Belum ada jadwal di kategori ini.</p>
            </div>
        @endforelse
    </div>
@endforeach
@endsection