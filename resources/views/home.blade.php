@extends('layouts.app')

@section('title', 'K-PLAY — Your World. Your Idols. Your K-Pop.')

@section('content')

{{-- ================= EPIC OPENING SPLASH ================= --}}
<div id="kplaySplash"
     class="fixed inset-0 z-[9999] bg-[#0a0a0f] flex items-center justify-center overflow-hidden transition-opacity duration-1000">

    {{-- Background Glow --}}
    <div class="absolute inset-0">
        <div class="absolute top-1/3 left-1/4 w-[500px] h-[500px] bg-[#d946ef]/30 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-1/3 right-1/4 w-[500px] h-[500px] bg-[#06b6d4]/30 rounded-full blur-[150px] animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    {{-- Konten Splash --}}
    <div class="relative text-center px-4">

        {{-- Logo Animasi --}}
        <div id="splashLogo" class="mb-8 opacity-0 scale-50 transition-all duration-1000">
            <h1 class="text-6xl md:text-8xl font-display font-black tracking-tight">
                <span class="text-white">K-PLAY</span><span class="text-[#d946ef]">.</span>
            </h1>
        </div>

        {{-- Garis Loading --}}
        <div class="w-64 h-1 bg-white/10 rounded-full mx-auto overflow-hidden mb-6 opacity-0" id="splashBar">
            <div id="splashBarFill" class="h-full w-0 bg-gradient-to-r from-[#d946ef] to-[#06b6d4] rounded-full transition-all duration-[2000ms] ease-out"></div>
        </div>

        {{-- Teks Loading --}}
        <p id="splashText" class="text-sm text-zinc-500 tracking-[4px] uppercase opacity-0">
            Entering the Universe
        </p>

    </div>

</div>

{{-- ================= HERO SECTION ================= --}}
<section id="heroSection" class="relative min-h-screen flex items-center justify-center overflow-hidden opacity-0 transition-opacity duration-1000">

    {{-- Background Glow --}}
    <div class="absolute inset-0 -z-10">
        <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-[#d946ef]/20 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[600px] h-[600px] bg-[#06b6d4]/20 rounded-full blur-[150px] animate-pulse" style="animation-delay: 1.5s;"></div>
    </div>

    {{-- Grid Pattern Overlay --}}
    <div class="absolute inset-0 -z-10 opacity-[0.03]"
         style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 60px 60px;">
    </div>

    <div class="max-w-5xl mx-auto text-center px-4 py-20 relative">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#d946ef]/10 border border-[#d946ef]/30 mb-8 animate-[fadeInUp_1s_ease_0.2s_both]">
            <span class="w-2 h-2 rounded-full bg-[#d946ef] animate-pulse"></span>
            <span class="text-xs font-bold tracking-[3px] text-[#d946ef] uppercase">
                Welcome to K-PLAY Universe
            </span>
        </div>

        {{-- Judul Besar --}}
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-display font-black leading-[1.05] tracking-tight mb-6">
            <span class="block text-white animate-[fadeInUp_1s_ease_0.4s_both]">YOUR WORLD.</span>
            <span class="block text-white animate-[fadeInUp_1s_ease_0.6s_both]">YOUR IDOLS.</span>
            <span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#d946ef] via-[#a855f7] to-[#06b6d4] animate-[fadeInUp_1s_ease_0.8s_both]">
                YOUR K-POP.
            </span>
        </h1>

        {{-- Deskripsi --}}
        <p class="text-base md:text-lg text-zinc-400 max-w-2xl mx-auto mb-10 leading-relaxed animate-[fadeInUp_1s_ease_1s_both]">
            Satu tempat untuk mengenal idol, menemukan komunitas, berbagi momen,
            dan terhubung dengan sesama penggemar K-Pop.
        </p>

        {{-- Tombol CTA --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-[fadeInUp_1s_ease_1.2s_both]">
            <a href="{{ route('idols.index') }}"
               class="group relative w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-[#d946ef] to-[#06b6d4] text-white font-bold text-base overflow-hidden transition-all hover:scale-105 shadow-lg shadow-[#d946ef]/30">
                <span class="relative z-10">Explore Now →</span>
                <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
            </a>
            <a href="{{ route('community.index') }}"
               class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white/5 border border-[#1f1f27] text-white font-bold text-base hover:bg-white/10 hover:border-[#d946ef]/50 transition-all hover:scale-105">
                Join Community
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mt-20 max-w-2xl mx-auto animate-[fadeInUp_1s_ease_1.4s_both]">
            <div class="text-center">
                <p class="text-3xl md:text-4xl font-display font-black text-white">100+</p>
                <p class="text-xs text-zinc-500 uppercase tracking-wider mt-1">Idols</p>
            </div>
            <div class="text-center border-x border-[#1f1f27]">
                <p class="text-3xl md:text-4xl font-display font-black text-white">50+</p>
                <p class="text-xs text-zinc-500 uppercase tracking-wider mt-1">Groups</p>
            </div>
            <div class="text-center">
                <p class="text-3xl md:text-4xl font-display font-black text-white">1K+</p>
                <p class="text-xs text-zinc-500 uppercase tracking-wider mt-1">Fans</p>
            </div>
        </div>

    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce opacity-50">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2" class="text-zinc-500">
            <path d="m6 9 6 6 6-6"></path>
        </svg>
    </div>

</section>

{{-- ================= ANIMATION KEYFRAMES ================= --}}
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

{{-- ================= SPLASH SCRIPT ================= --}}
<script>
    (function () {
        const splash     = document.getElementById('kplaySplash');
        const logo       = document.getElementById('splashLogo');
        const bar        = document.getElementById('splashBar');
        const barFill    = document.getElementById('splashBarFill');
        const text       = document.getElementById('splashText');
        const hero       = document.getElementById('heroSection');

        // Cek apakah splash sudah pernah tampil di session ini
        const hasSeenSplash = sessionStorage.getItem('kplay_splash_seen');

        if (hasSeenSplash) {
            // Langsung tampilkan hero tanpa splash
            splash.style.display = 'none';
            hero.classList.remove('opacity-0');
            hero.classList.add('opacity-100');
            return;
        }

        // Tandai bahwa splash sudah tampil
        sessionStorage.setItem('kplay_splash_seen', '1');

        // Animasi splash
        setTimeout(() => {
            logo.classList.remove('opacity-0', 'scale-50');
            logo.classList.add('opacity-100', 'scale-100');
        }, 200);

        setTimeout(() => {
            bar.classList.remove('opacity-0');
            bar.classList.add('opacity-100');
        }, 800);

        setTimeout(() => {
            barFill.style.width = '100%';
        }, 1000);

        setTimeout(() => {
            text.classList.remove('opacity-0');
            text.classList.add('opacity-100');
        }, 1200);

        // Selesai splash → fade out → tampilkan hero
        setTimeout(() => {
            splash.classList.add('opacity-0');
            hero.classList.remove('opacity-0');
            hero.classList.add('opacity-100');

            setTimeout(() => {
                splash.style.display = 'none';
            }, 1000);
        }, 3200);
    })();
</script>

@endsection