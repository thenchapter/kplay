@extends('layouts.app')
@php use App\Support\Sx; @endphp

@section('title', 'News')
@section('heading', 'News')
@section('sub', 'Berita K-Pop terbaru')

@section('content')
<!-- Tombol Navigasi Tab -->
<div class="flex gap-3 mb-8">
    <a href="{{ route('news.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-accent to-purple-600 text-white shadow-lg">📰 Berita</a>
    <a href="{{ route('news.calendar') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-surface border border-border text-zinc-400 hover:text-white transition">📅 Kalender Comeback</a>
</div>

@if(count($news))
    @php 
        $f = $news[0]; 
        // Ambil gambar (image), jika kosong pakai placeholder
        $fi = Sx::get($f, 'image', 'gambar', 'foto', 'thumbnail'); 
        $ft = Sx::get($f, 'judul', 'title');
    @endphp

    <!-- Berita Utama (Headline) -->
    <a href="{{ route('news.show', $f['id'] ?? 0) }}" class="group relative block rounded-3xl overflow-hidden border border-border mb-8 card-hover min-h-[320px] bg-surface">
        <img src="{{ $fi ?: Sx::ph($ft, '7c3aed') }}" 
             referrerpolicy="no-referrer" 
             onerror="this.src='{{ Sx::ph($ft, '7c3aed') }}'" 
             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
        <div class="relative p-8 flex flex-col justify-end min-h-[320px]">
            <span class="text-xs font-bold text-accent uppercase tracking-wider">{{ Sx::get($f, 'kategori', 'category') ?? 'Berita' }}</span>
            <h2 class="font-display font-extrabold text-2xl md:text-4xl max-w-3xl mt-1 text-white">{{ $ft }}</h2>
            <p class="text-xs text-zinc-300 mt-2">{{ Sx::date(Sx::get($f, 'tanggal', 'created_at', 'date')) }}</p>
        </div>
    </a>

    <!-- Grid Berita Lainnya -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($news->slice(1) as $n)
            @php 
                $ni = Sx::get($n, 'image', 'gambar', 'foto', 'thumbnail'); 
                $nt = Sx::get($n, 'judul', 'title'); 
            @endphp
            <a href="{{ route('news.show', $n['id'] ?? 0) }}" class="card-hover bg-surface border border-border rounded-2xl overflow-hidden flex flex-col">
                <img src="{{ $ni ?: Sx::ph($nt, '06b6d4') }}" 
                     loading="lazy" 
                     referrerpolicy="no-referrer" 
                     onerror="this.src='{{ Sx::ph($nt, '06b6d4') }}'" 
                     class="h-44 w-full object-cover">
                <div class="p-5 flex flex-col flex-1">
                    <span class="text-[11px] font-bold text-cyan uppercase tracking-wider">{{ Sx::get($n, 'kategori', 'category') ?? 'Berita' }}</span>
                    <h3 class="font-display font-bold text-lg clamp2 mt-1 leading-snug">{{ $nt }}</h3>
                    <p class="text-sm text-zinc-400 clamp2 mt-2 flex-1">{{ Sx::get($n, 'ringkasan', 'isi', 'konten', 'content') }}</p>
                    <p class="text-xs text-zinc-500 mt-4 pt-3 border-t border-border/50">{{ Sx::date(Sx::get($n, 'tanggal', 'created_at', 'date')) }}</p>
                </div>
            </a>
        @endforeach
    </div>
@else
    <!-- Tampilan jika belum ada berita -->
    <div class="text-center py-20 bg-surface border border-dashed border-border rounded-2xl">
        <div class="text-5xl mb-4">📰</div>
        <h3 class="text-xl font-bold text-white mb-2">Belum Ada Berita</h3>
        <p class="text-zinc-400">Berita terbaru seputar K-Pop akan segera hadir di sini.</p>
    </div>
@endif
@endsection