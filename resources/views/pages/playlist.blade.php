@extends('layouts.app')

@php
    use App\Support\Sx;

    $playlists = $playlists ?? [];
    $total     = count($playlists);

    $tabs = [
        ['label' => 'Profil',     'route' => 'profile.index',         'url' => '/profile'],
        ['label' => 'Bias',       'route' => 'profile.bias',          'url' => '/profile/bias'],
        ['label' => 'Grup',       'route' => 'profile.groups',        'url' => '/profile/groups'],
        ['label' => 'Playlist',   'route' => 'profile.playlist',      'url' => '/profile/playlist'],
        ['label' => 'Notifikasi', 'route' => 'profile.notifications', 'url' => '/profile/notifications'],
    ];
@endphp

@section('title', 'Playlist')

@section('content')
<div class="max-w-2xl mx-auto -mt-6 pb-32">

    {{-- TAB MENU --}}
    <div class="sticky top-16 z-40 bg-[#0a0a0f]/95 backdrop-blur border-b border-[#1f1f27]">
        <div class="flex overflow-x-auto scrollbar-hide">
            @foreach($tabs as $tab)
                @php
                    $href     = \Illuminate\Support\Facades\Route::has($tab['route']) ? route($tab['route']) : url($tab['url']);
                    $isActive = $tab['route'] === 'profile.playlist';
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
            <span class="text-white font-bold">{{ $total }}</span> lagu di playlist-mu
        </p>
    </div>

    {{-- LIST LAGU --}}
    @if($total > 0)
        <div class="divide-y divide-[#1f1f27]">
            @foreach($playlists as $i => $p)
                @php
                    $judul  = $p['judul'] ?? 'Judul';
                    $artist = $p['artist'] ?? 'Artist';
                    $durasi = $p['durasi'] ?? '';
                    $link   = $p['link'] ?? '';

                    // Extract YouTube video ID
                    $youtubeId = '';
                    if ($link && preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $link, $matches)) {
                        $youtubeId = $matches[1];
                    }

                    $thumbnail = $youtubeId ? "https://i.ytimg.com/vi/{$youtubeId}/hqdefault.jpg" : '';
                @endphp

                {{-- CARD LAGU --}}
                <div class="group flex items-center gap-3 py-3 px-2 hover:bg-white/[0.03] transition-colors cursor-pointer"
                     onclick="playSong({{ $i }}, '{{ $youtubeId }}', '{{ addslashes($judul) }}', '{{ addslashes($artist) }}', '{{ $thumbnail }}')">

                    {{-- NOMOR / PLAY --}}
                    <div class="w-6 shrink-0 flex justify-center">
                        <span class="text-sm text-zinc-600 font-mono group-hover:hidden">{{ $i + 1 }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="text-[#d946ef] hidden group-hover:block">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                    </div>

                    {{-- COVER --}}
                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-[#121218] shrink-0 relative">
                        @if($thumbnail)
                            <img src="{{ $thumbnail }}" alt="{{ $judul }}" loading="lazy" referrerpolicy="no-referrer"
                                 onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-cover').style.display='flex';"
                                 class="w-full h-full object-cover">
                        @endif
                        <div class="fallback-cover {{ $thumbnail ? 'hidden' : 'flex' }} absolute inset-0 items-center justify-center bg-gradient-to-br from-[#1a1228] to-[#0f1a1f]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d946ef" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                            </svg>
                        </div>
                    </div>

                    {{-- INFO --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $judul }}</p>
                        <p class="text-xs text-zinc-500 truncate">{{ $artist }}</p>
                    </div>

                    {{-- DURASI --}}
                    @if($durasi)
                        <span class="text-xs text-zinc-600 font-mono shrink-0">{{ $durasi }}</span>
                    @endif

                    {{-- LINK YOUTUBE --}}
                    @if($link)
                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                           onclick="event.stopPropagation()"
                           title="Buka di YouTube"
                           class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-red-400 hover:bg-red-500/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- PLAYER BAR --}}
        <div id="playerBar" class="hidden fixed bottom-0 left-0 right-0 z-[100] bg-[#0a0a0f]/95 backdrop-blur-xl border-t border-[#1f1f27] p-3">
            <div class="max-w-2xl mx-auto">
                <div class="flex items-center gap-3 mb-2">
                    <img id="playerCover" src="" alt="" class="w-10 h-10 rounded object-cover">
                    <div class="flex-1 min-w-0">
                        <p id="playerTitle" class="text-sm font-bold text-white truncate">-</p>
                        <p id="playerArtist" class="text-xs text-zinc-500 truncate">-</p>
                    </div>
                    <button onclick="closePlayer()" class="w-8 h-8 rounded-full text-zinc-500 hover:text-white hover:bg-white/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>
                <div id="playerFrame" class="w-full aspect-video rounded-lg overflow-hidden"></div>
            </div>
        </div>

    @else

        {{-- EMPTY STATE --}}
        <div class="text-center py-24 px-4">
            <div class="w-16 h-16 rounded-full bg-[#121218] border border-[#1f1f27] flex items-center justify-center mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-zinc-500">
                    <path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Playlist Kosong</h3>
            <p class="text-sm text-zinc-500">Belum ada lagu yang ditambahkan.</p>
        </div>

    @endif

</div>
@endsection

@push('scripts')
<script>
    function playSong(index, youtubeId, judul, artist, cover) {
        if (!youtubeId) {
            alert('Lagu ini belum punya link YouTube yang valid.');
            return;
        }

        const playerBar    = document.getElementById('playerBar');
        const playerFrame  = document.getElementById('playerFrame');
        const playerTitle  = document.getElementById('playerTitle');
        const playerArtist = document.getElementById('playerArtist');
        const playerCover  = document.getElementById('playerCover');

        playerTitle.textContent  = judul;
        playerArtist.textContent = artist;
        playerCover.src          = cover || '';

        playerFrame.innerHTML = `
            <iframe width="100%" height="100%"
                    src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
        `;

        playerBar.classList.remove('hidden');
        
        // Scroll ke player bar dengan halus
        setTimeout(() => {
            playerBar.scrollIntoView({ behavior: 'smooth', block: 'end' });
        }, 100);
    }

    function closePlayer() {
        const playerBar   = document.getElementById('playerBar');
        const playerFrame = document.getElementById('playerFrame');
        playerFrame.innerHTML = '';
        playerBar.classList.add('hidden');
    }
</script>
@endpush