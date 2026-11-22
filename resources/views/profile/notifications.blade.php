@extends('layouts.app')

@php
    $notifications = $notifications ?? [];
    $unreadCount   = $unreadCount ?? 0;

    $icons = [
        'bias'      => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
        'community' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
        'comeback'  => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>',
        'gallery'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>',
        'system'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
    ];

    $colors = [
        'bias'      => 'text-pink-400 bg-pink-500/10',
        'community' => 'text-cyan-400 bg-cyan-500/10',
        'comeback'  => 'text-amber-400 bg-amber-500/10',
        'gallery'   => 'text-violet-400 bg-violet-500/10',
        'system'    => 'text-zinc-400 bg-zinc-500/10',
    ];

    $timeAgo = function ($ts) {
        $diff = now()->timestamp - $ts;
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 604800) return floor($diff / 86400) . ' hari lalu';
        return date('d M Y', $ts);
    };

    $tabs = [
        ['label' => 'Profil',     'route' => 'profile.index',         'url' => '/profile'],
        ['label' => 'Bias',       'route' => 'profile.bias',          'url' => '/profile/bias'],
        ['label' => 'Grup',       'route' => 'profile.groups',        'url' => '/profile/groups'],
        ['label' => 'Playlist',   'route' => 'profile.playlist',      'url' => '/profile/playlist'],
        ['label' => 'Notifikasi', 'route' => 'profile.notifications', 'url' => '/profile/notifications'],
    ];
@endphp

@section('title', 'Notifikasi')

@section('content')

<div class="max-w-2xl mx-auto -mt-6">

    {{-- TAB MENU --}}
    <div class="sticky top-16 z-40 bg-[#0a0a0f]/95 backdrop-blur border-b border-[#1f1f27]">
        <div class="flex overflow-x-auto scrollbar-hide">
            @foreach($tabs as $tab)
                @php
                    $href     = \Illuminate\Support\Facades\Route::has($tab['route']) ? route($tab['route']) : url($tab['url']);
                    $isActive = $tab['route'] === 'profile.notifications';
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
            @if($unreadCount > 0)
                <span class="text-[#d946ef] font-bold">{{ $unreadCount }}</span> notifikasi baru
            @else
                Semua notifikasi sudah dibaca
            @endif
        </p>
        @if($unreadCount > 0)
            <button class="text-[11px] font-bold text-[#d946ef] hover:text-white transition">
                Tandai Dibaca
            </button>
        @endif
    </div>

    {{-- LIST NOTIFIKASI --}}
    @if(count($notifications) > 0)

        <div class="divide-y divide-[#1f1f27]">

            @foreach($notifications as $notif)
                @php
                    $icon     = $icons[$notif['type']] ?? $icons['system'];
                    $color    = $colors[$notif['type']] ?? $colors['system'];
                    $isUnread = empty($notif['read']);
                @endphp

                <a href="{{ $notif['url'] ?? '#' }}"
                   class="flex items-start gap-4 px-4 py-4 transition-colors
                          {{ $isUnread ? 'bg-[#d946ef]/[0.03] hover:bg-[#d946ef]/[0.06]' : 'hover:bg-white/[0.03]' }}">

                    {{-- IKON --}}
                    <span class="w-10 h-10 rounded-full {{ $color }} flex items-center justify-center shrink-0">
                        {!! $icon !!}
                    </span>

                    {{-- KONTEN --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-semibold text-white leading-snug {{ $isUnread ? '' : 'text-zinc-300' }}">
                                {{ $notif['title'] }}
                            </p>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-[#d946ef] shrink-0 mt-1.5"></span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $notif['message'] }}
                        </p>
                        <p class="text-[10px] text-zinc-600 mt-1.5">{{ $timeAgo($notif['time']) }}</p>
                    </div>

                </a>
            @endforeach

        </div>

    @else

        <div class="text-center py-24 px-4">
            <div class="w-16 h-16 rounded-full bg-[#121218] border border-[#1f1f27] flex items-center justify-center mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5" class="text-zinc-500">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Belum Ada Notifikasi</h3>
            <p class="text-sm text-zinc-500">Notifikasi baru akan muncul di sini.</p>
        </div>

    @endif

</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection