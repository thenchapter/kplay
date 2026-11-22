{{-- Menu pengguna di pojok kanan navbar --}}

@php
    $su = session('user');
    if (!is_array($su)) $su = null;

    $suNama  = $su['nama'] ?? $su['name'] ?? 'User';
    $suEmail = $su['email'] ?? '';
    
    // Bersihkan username agar tidak double '@'
    $rawUsername = $su['username'] ?? '';
    $suUn = $rawUsername ? '@' . ltrim($rawUsername, '@') : '';

    // Avatar: prioritas URL langsung, kalau tidak ada pakai UI-Avatars
    $suAv = $su['avatar'] ?? $su['foto'] ?? null;
    if (!$suAv || !str_starts_with($suAv, 'http')) {
        $suAv = 'https://ui-avatars.com/api/?size=200&background=d946ef&color=fff&name=' . urlencode($suNama);
    }

    $dropdownItems = [
        ['label' => 'Profil Saya', 'route' => 'profile.index', 'url' => '/profile', 'icon' => '👤'],
        ['label' => 'My Bias', 'route' => 'profile.bias', 'url' => '/profile/bias', 'icon' => '💜'],
        ['label' => 'Grup Favorit', 'route' => 'profile.groups', 'url' => '/profile/groups', 'icon' => '⭐'],
        ['label' => 'Notifikasi', 'route' => 'profile.notifications', 'url' => '/profile/notifications', 'icon' => '🔔'],
    ];
@endphp

@if($su)
<div x-data="{ open: false }" class="relative flex items-center gap-2">
    
    {{-- AVATAR (Klik untuk ke profil) --}}
    <a href="{{ route('profile.index') }}" title="Buka Profil" class="relative group">
        <img src="{{ $suAv }}"
             alt="{{ $suNama }}"
             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?size=200&background=d946ef&color=fff&name={{ urlencode($suNama) }}'"
             class="w-10 h-10 rounded-full object-cover ring-2 ring-[#d946ef] group-hover:ring-[#06b6d4] transition-all">
        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#0a0a0f]"></span>
    </a>

    {{-- NAMA + CHEVRON (Klik untuk dropdown) --}}
    <button @click="open = !open" @click.outside="open = false" class="hidden sm:flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-white/5 transition-colors">
        <span class="text-right leading-tight">
            <span class="block text-sm font-bold text-white">{{ $suNama }}</span>
            @if($suUn)
                <span class="block text-[11px] text-zinc-400">{{ $suUn }}</span>
            @endif
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
    </button>

    {{-- DROPDOWN MENU --}}
    <div x-show="open" x-cloak x-transition.opacity
         class="absolute right-0 top-full mt-3 w-64 bg-[#121218] border border-[#1f1f27] rounded-2xl shadow-2xl shadow-black/50 p-2 z-[60]">
        
        <div class="px-3 py-3 border-b border-[#1f1f27] mb-1">
            <p class="font-bold text-sm text-white truncate">{{ $suNama }}</p>
            @if($suEmail)
                <p class="text-xs text-zinc-400 truncate">{{ $suEmail }}</p>
            @endif
        </div>

        @foreach($dropdownItems as $item)
            @if(Route::has($item['route']))
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-zinc-300 hover:bg-white/5 hover:text-white transition-colors">
                    <span class="text-base">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach

        <form action="{{ route('logout') }}" method="POST" class="mt-1 pt-1 border-t border-[#1f1f27]">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                <span class="text-base">🚪</span>
                Logout
            </button>
        </form>
    </div>

</div>
@else
    <a href="{{ route('login') }}"
       class="text-sm font-bold bg-white text-black px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-colors">
        Login
    </a>
@endif