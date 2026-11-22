@extends('layouts.app')
@php use App\Support\Sx; @endphp
@section('title','Community')@section('heading','Community')@section('sub','Berbagi cerita dan berdiskusi bersama sesama fans')
@section('content')
<div class="max-w-3xl">
@if(session('user'))
<form method="POST" action="{{ route('community.store') }}" class="bg-surface border border-border rounded-2xl p-5 mb-8 space-y-3">@csrf
    <input name="judul" placeholder="Judul (opsional)" class="w-full bg-bg border border-border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-accent">
    <textarea name="isi" rows="3" required placeholder="Apa yang ingin kamu bagikan?" class="w-full bg-bg border border-border rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-accent"></textarea>
    <div class="flex gap-3"><input name="gambar" type="url" placeholder="Link gambar (opsional)" class="flex-1 bg-bg border border-border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-accent"><button class="bg-gradient-to-r from-accent to-purple-600 font-bold px-6 rounded-xl text-sm">Posting</button></div>
</form>
@else
<div class="bg-surface border border-dashed border-border rounded-2xl p-5 mb-8 text-sm text-zinc-400">Mau ikut diskusi? <a class="text-accent font-bold" href="{{ route('login') }}">Login dulu</a>.</div>
@endif
<div class="space-y-5">
@forelse($posts as $p)
    @php $au = Sx::user(Sx::get($p,'user_id','id_user')); $an = Sx::get($au ?? [],'nama','name') ?? 'Anggota K-PLAY'; $img = Sx::get($p,'gambar','foto','image'); @endphp
    <article class="bg-surface border border-border rounded-2xl p-5 card-hover">
        <div class="flex items-center gap-3 mb-3"><img src="{{ Sx::get($au ?? [],'avatar','foto') ?: Sx::ph($an) }}" referrerpolicy="no-referrer" onerror="this.src='{{ Sx::ph($an) }}'" class="w-10 h-10 rounded-full object-cover">
            <div><p class="font-bold text-sm">{{ $an }}</p><p class="text-xs text-zinc-500">{{ Sx::ago(Sx::get($p,'created_at','tanggal','date')) }}</p></div></div>
        <a href="{{ route('community.show',$p['id'] ?? 0) }}">
            @if(Sx::get($p,'judul','title'))<h3 class="font-display font-bold text-lg mb-1">{{ Sx::get($p,'judul','title') }}</h3>@endif
            <p class="text-sm text-zinc-300 clamp2">{{ Sx::get($p,'isi','konten','content','body') }}</p>
            @if($img)<img src="{{ $img }}" loading="lazy" referrerpolicy="no-referrer" class="mt-3 rounded-xl max-h-80 w-full object-cover">@endif
        </a>
        <div class="flex gap-5 mt-4 text-xs text-zinc-400"><span>♥ {{ Sx::get($p,'likes') ?? 0 }}</span><a href="{{ route('community.show',$p['id'] ?? 0) }}" class="hover:text-white">💬 {{ $counts[(string)($p['id'] ?? '')] ?? 0 }} komentar</a></div>
    </article>
@empty
    <p class="text-center py-16 text-zinc-400 bg-surface border border-dashed border-border rounded-2xl">Belum ada postingan.</p>
@endforelse
</div></div>
@endsection
