<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'K-PLAY')</title>

    {{-- Vite dengan fallback --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            neon: { pink: '#ff2d95', cyan: '#00f0ff', violet: '#8b5cf6', purple: '#d946ef' },
                            ink: { 950: '#05030f', 900: '#0a0620', 800: '#120a2e', 700: '#1a0f3d' },
                            accent: '#d946ef',
                            'accent-hover': '#c026d3',
                            surface: '#121218',
                            border: '#1f1f27',
                        },
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                            display: ['Outfit', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                    },
                },
            };
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
        
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    @endif

    <style>
        body { font-family: 'Inter', sans-serif; background: #05030f; overflow-x: hidden; }
        .font-display { font-family: 'Outfit', sans-serif; letter-spacing: -0.02em; }
        .gradient-text {
            background: linear-gradient(135deg, #f472b6, #a855f7, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(168, 85, 247, 0.15);
        }
        .card-neon { transition: all .3s cubic-bezier(.4,0,.2,1); }
        .card-neon:hover {
            transform: translateY(-6px);
            border-color: rgba(236, 72, 153, 0.5);
            box-shadow: 0 20px 50px -15px rgba(168, 85, 247, .4);
        }
        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #05030f; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #ff2d95, #8b5cf6); border-radius: 999px; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-ink-950 text-white antialiased">

    {{-- ================= NAVBAR ================= --}}
    <nav class="sticky top-0 z-50 bg-[#0a0a0f]/90 backdrop-blur border-b border-[#1f1f27]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group">
                    <span class="text-2xl font-display font-extrabold text-white transition-transform group-hover:scale-105">
                        K-PLAY<span class="text-[#d946ef]">.</span>
                    </span>
                </a>

                {{-- Menu Navigasi Desktop --}}
                <div class="hidden md:flex items-center gap-1">
                    @php
                        $navItems = [
                            ['route' => 'home', 'label' => 'Home', 'active' => 'home'],
                            ['route' => 'idols.index', 'label' => 'Idols', 'active' => 'idols.*'],
                            ['route' => 'groups.index', 'label' => 'Groups', 'active' => 'groups.*'],
                            ['route' => 'community.index', 'label' => 'Community', 'active' => 'community.*'],
                            ['route' => 'news.index', 'label' => 'News', 'active' => 'news.*'],
                            ['route' => 'playlist.index', 'label' => 'Playlist', 'active' => 'playlist.*'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors 
                                  {{ request()->routeIs($item['active']) ? 'text-white bg-white/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                {{-- Kanan: Notifikasi & User Menu --}}
                <div class="flex items-center gap-2 shrink-0">
                    @php
                        $notifUrl = \Illuminate\Support\Facades\Route::has('profile.notifications')
                            ? route('profile.notifications')
                            : url('/profile/notifications');
                        $notifCount = session('notif_count', 0);
                    @endphp

                    <a href="{{ $notifUrl }}" title="Notifikasi"
                       class="relative w-10 h-10 rounded-full flex items-center justify-center text-zinc-400 hover:text-white hover:bg-white/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                    @include('partials.user-menu')
                </div>
            </div>
        </div>
    </nav>

    {{-- ================= KONTEN UTAMA ================= --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-screen">

        {{-- Heading --}}
        @hasSection('heading')
            <div class="mb-6" data-aos="fade-down">
                <h1 class="text-2xl md:text-3xl font-display font-extrabold text-white">@yield('heading')</h1>
                @hasSection('sub')
                    <p class="text-sm text-zinc-400 mt-1">@yield('sub')</p>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="border-t border-[#1f1f27] mt-20 py-8 text-center text-sm text-zinc-500">
        <p>&copy; {{ date('Y') }} K-PLAY. Your World. Your Idols. Your K-Pop.</p>
    </footer>

    {{-- Scripts --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        AOS.init({ duration: 700, once: true, offset: 60, easing: 'ease-out-cubic' });
    </script>
    @stack('scripts')

</body>
</html>