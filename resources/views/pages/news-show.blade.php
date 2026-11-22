@extends('layouts.app')
@php use App\Support\Sx; @endphp

@section('title', Sx::get($article ?? [], 'judul', 'title') . ' — K-PLAY')
@section('heading', 'Berita')
@section('sub', 'Detail Berita')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Tombol Kembali -->
    <a href="{{ route('news.index') }}" class="inline-flex items-center text-sm font-bold text-zinc-400 hover:text-accent mb-6 transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Berita
    </a>

    <article class="bg-surface border border-border rounded-3xl overflow-hidden">
        @php 
            $img = Sx::get($article ?? [], 'image', 'gambar', 'foto', 'thumbnail'); 
            $title = Sx::get($article ?? [], 'judul', 'title');
        @endphp

        <!-- Gambar Utama -->
        @if(!empty($img))
            <div class="w-full max-h-[450px] overflow-hidden bg-zinc-900">
                <img src="{{ $img }}" alt="{{ $title }}" 
                     class="w-full h-full object-cover"
                     referrerpolicy="no-referrer"
                     onerror="this.style.display='none'; this.parentElement.style.display='none';">
            </div>
        @endif

        <div class="p-6 md:p-10">
            <!-- Kategori & Tanggal -->
            <div class="flex items-center gap-3 text-xs font-bold mb-4">
                <span class="text-accent uppercase tracking-wider">{{ Sx::get($article ?? [], 'kategori', 'category') ?? 'Berita' }}</span>
                <span class="text-zinc-600">•</span>
                <span class="text-zinc-400">{{ Sx::date(Sx::get($article ?? [], 'tanggal', 'created_at', 'date')) }}</span>
            </div>

            <!-- Judul Berita -->
            <h1 class="text-3xl md:text-5xl font-display font-bold mb-6 leading-tight">{{ $title ?? 'Tanpa Judul' }}</h1>

            <!-- Isi Konten -->
            <div class="prose prose-invert prose-pink max-w-none text-zinc-300 leading-relaxed text-sm md:text-base">
                {!! nl2br(e(Sx::get($article ?? [], 'ringkasan', 'isi', 'konten', 'content', 'description') ?? 'Isi berita belum tersedia.')) !!}
            </div>
        </div>
    </article>
</div>
@endsection