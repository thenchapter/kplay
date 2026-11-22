{{-- ================= NAVBAR ================= --}}
<nav class="sticky top-0 z-50 bg-[#0a0a0f]/90 backdrop-blur border-b border-[#1f1f27]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <span class="text-2xl font-display font-extrabold text-white">
                    K-PLAY<span class="text-[#d946ef]">.</span>
                </span>
            </a>

            {{-- Menu Navigasi (Desktop) --}}
            <div class="hidden md:flex items-center gap-1">

                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'text-white bg-white/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    Home
                </a>

                <a href="{{ route('idols.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('idols.*') ? 'text-white bg-white/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    Idols
                </a>

                <a href="{{ route('groups.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('groups.*') ? 'text-white bg-white/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    Groups
                </a>

                <a href="{{ route('community.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('community.*') ? 'text-white bg-white/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    Community
                </a>

                <a href="{{ route('news.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('news.*') ? 'text-white bg-white/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    News
                </a>

            </div>

            {{-- KANAN: NOTIFIKASI + USER MENU --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- NOTIFIKASI --}}
                @php
                    $notifUrl = \Illuminate\Support\Facades\Route::has('profile.notifications')
                        ? route('profile.notifications')
                        : url('/profile/notifications');
                    $notifCount = session('notif_count', 0);
                @endphp

                <a href="{{ $notifUrl }}"
                   title="Notifikasi"
                   class="relative w-10 h-10 rounded-full flex items-center justify-center text-zinc-400 hover:text-white hover:bg-white/5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>

                    @if($notifCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-[#d946ef] text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-[#0a0a0f]">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                    @else
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#d946ef] ring-2 ring-[#0a0a0f]"></span>
                    @endif
                </a>

                {{-- USER MENU --}}
                @include('partials.user-menu')

            </div>

        </div>
    </div>
</nav>