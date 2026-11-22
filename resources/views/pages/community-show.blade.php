@extends('layouts.app')
@php use App\Support\Sx; $au = Sx::user(Sx::get($post,'user_id','id_user')); $an = Sx::get($au ?? [],'nama','name') ?? 'Anggota K-PLAY'; $img = Sx::get($post,'gambar','foto','image'); @endphp
@section('title','Diskusi')@section('heading', Sx::get($post,'judul','title') ?: 'Diskusi')@section('sub','oleh '.$an.' · '.Sx::ago(Sx::get($post,'created_at','tanggal','date')))
@section('content')
<div class="max-w-3xl space-y-6">
    <div class="bg-surface border border-border rounded-2xl p-6"><p class="text-zinc-200 leading-relaxed whitespace-pre-line">{{ Sx::get($post,'isi','konten','content','body') }}</p>
        @if($img)<img src="{{ $img }}" referrerpolicy="no-referrer" class="mt-4 rounded-xl w-full object-cover">@endif</div>
    <h2 class="font-display font-bold text-xl">{{ count($comments) }} Komentar</h2>
    @if(session('user'))
    <form method="POST" action="{{ route('community.comment',$post['id']) }}" class="flex gap-3">@csrf
        <input name="isi" required placeholder="Tulis komentar..." class="flex-1 bg-surface border border-border rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-accent"><button class="bg-gradient-to-r from-accent to-purple-600 font-bold px-6 rounded-xl text-sm">Kirim</button></form>
    @else<p class="text-sm text-zinc-400"><a class="text-accent font-bold" href="{{ route('login') }}">Login</a> untuk berkomentar.</p>@endif
    @foreach($comments as $c)@php $cu = Sx::user(Sx::get($c,'user_id','id_user')); $cn = Sx::get($cu ?? [],'nama','name') ?? 'Anggota K-PLAY'; @endphp
    <div class="flex gap-3"><img src="{{ Sx::get($cu ?? [],'avatar','foto') ?: Sx::ph($cn) }}" referrerpolicy="no-referrer" onerror="this.src='{{ Sx::ph($cn) }}'" class="w-9 h-9 rounded-full object-cover">
        <div class="bg-surface border border-border rounded-2xl px-4 py-3 flex-1"><p class="text-sm"><b>{{ $cn }}</b> <span class="text-xs text-zinc-500">{{ Sx::ago(Sx::get($c,'created_at','tanggal','date')) }}</span></p><p class="text-sm text-zinc-300">{{ Sx::get($c,'isi','komentar','konten','content') }}</p></div></div>
    @endforeach
    <a href="{{ route('community.index') }}" class="inline-block text-sm font-bold text-accent">← Kembali ke Community</a>
</div>
@endsection
