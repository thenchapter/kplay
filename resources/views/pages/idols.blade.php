@extends('layouts.app')

@php
    use App\Support\Sx;

    $totalIdols = count($idols);
    $groups     = $groups ?? [];
@endphp

@section('title', 'Idols')

@section('content')

<style>
    /* ============================================================
       PARTICLE BACKGROUND
       ============================================================ */
    #idols-particles {
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        opacity: .4;
    }

    /* ============================================================
       HERO ANIMATION
       ============================================================ */
    @keyframes heroFadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .hero-anim {
        animation: heroFadeIn 1s ease-out forwards;
        opacity: 0;
    }
    .hero-anim-delay-1 { animation-delay: .2s; }
    .hero-anim-delay-2 { animation-delay: .4s; }
    .hero-anim-delay-3 { animation-delay: .6s; }

    /* ============================================================
       GRADIENT TEXT
       ============================================================ */
    .gradient-text {
        background: linear-gradient(135deg, #d946ef, #a855f7, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        background-size: 200% 200%;
        animation: gradientShift 4s ease infinite;
    }
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50%      { background-position: 100% 50%; }
    }

    /* ============================================================
       CARD 3D TILT
       ============================================================ */
    .idol-card {
        transition: transform .4s cubic-bezier(.25,.46,.45,.94), box-shadow .4s;
        transform-style: preserve-3d;
        will-change: transform;
    }
    .idol-card:hover {
        box-shadow: 0 25px 50px -12px rgba(217,70,239,.25);
    }
    .idol-card .card-image {
        transition: transform .7s cubic-bezier(.25,.46,.45,.94);
    }
    .idol-card:hover .card-image {
        transform: scale(1.1);
    }

    /* ============================================================
       STAGGER ANIMATION
       ============================================================ */
    @keyframes cardFadeIn {
        from { opacity: 0; transform: translateY(40px) scale(.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .card-stagger {
        opacity: 0;
        animation: cardFadeIn .6s cubic-bezier(.25,.46,.45,.94) forwards;
    }

    /* ============================================================
       SEARCH GLOW
       ============================================================ */
    .search-input:focus {
        box-shadow: 0 0 0 3px rgba(217,70,239,.15), 0 0 20px rgba(217,70,239,.2);
        border-color: rgba(217,70,239,.5) !important;
    }

    /* ============================================================
       SCROLL REVEAL
       ============================================================ */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity .8s ease, transform .8s ease;
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ============================================================
       FILTER PILL
       ============================================================ */
    .filter-pill {
        transition: all .3s cubic-bezier(.25,.46,.45,.94);
    }
    .filter-pill:hover {
        transform: translateY(-2px);
    }
    .filter-pill.active {
        background: linear-gradient(135deg, #d946ef, #06b6d4);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 15px rgba(217,70,239,.4);
    }

    /* ============================================================
       SCROLLBAR HIDE
       ============================================================ */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* ============================================================
       SHIMMER LOADING
       ============================================================ */
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    .skeleton {
        background: linear-gradient(90deg, #121218 0%, #1f1f27 50%, #121218 100%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }

    /* ============================================================
       FLOATING BADGE
       ============================================================ */
    @keyframes floatBadge {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-6px); }
    }
    .float-badge {
        animation: floatBadge 3s ease-in-out infinite;
    }
</style>

{{-- PARTICLE BACKGROUND --}}
<canvas id="idols-particles"></canvas>

<div class="max-w-7xl mx-auto">

    {{-- ============================================================ --}}
    {{-- HERO HEADER --}}
    {{-- ============================================================ --}}
    <div class="relative mb-10 hero-anim">

        {{-- Background Glow --}}
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-[#d946ef]/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -top-10 -right-20 w-96 h-96 bg-[#06b6d4]/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row md:items-end justify-between gap-6">

            <div class="hero-anim hero-anim-delay-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#d946ef] animate-pulse"></span>
                    <span class="text-xs font-bold tracking-[3px] text-[#d946ef] uppercase">K-PLAY Directory</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-display font-black text-white leading-tight">
                    Discover Your<br>
                    <span class="gradient-text">Idols.</span>
                </h1>

                <p class="text-sm text-zinc-400 mt-4 max-w-lg leading-relaxed">
                    Temukan idol K-Pop favoritmu, kenali profil mereka, dan jelajahi berbagai grup dari dunia K-Pop.
                </p>
            </div>

            {{-- Stats --}}
            <div class="hero-anim hero-anim-delay-2 flex gap-4">
                <div class="bg-[#121218] border border-[#1f1f27] rounded-2xl px-6 py-4 text-center min-w-[110px] hover:border-[#d946ef]/40 transition-colors">
                    <p class="text-3xl font-display font-black text-white" data-count="{{ $totalIdols }}">0</p>
                    <p class="text-[10px] text-zinc-500 uppercase tracking-wider mt-1">Idols</p>
                </div>
                <div class="bg-[#121218] border border-[#1f1f27] rounded-2xl px-6 py-4 text-center min-w-[110px] hover:border-[#06b6d4]/40 transition-colors">
                    <p class="text-3xl font-display font-black text-white" data-count="{{ count($groups) }}">0</p>
                    <p class="text-[10px] text-zinc-500 uppercase tracking-wider mt-1">Groups</p>
                </div>
            </div>

        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- SEARCH & FILTER --}}
    {{-- ============================================================ --}}
    <div class="mb-8 hero-anim hero-anim-delay-3">

        <div class="flex flex-col lg:flex-row gap-3">

            {{-- SEARCH --}}
            <div class="flex-1 relative">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>

                <input
                    id="q"
                    type="text"
                    placeholder="Cari nama idol atau grup..."
                    autocomplete="off"
                    class="search-input w-full bg-[#121218] border border-[#1f1f27] rounded-2xl pl-12 pr-12 py-4 text-sm text-white placeholder:text-zinc-600 outline-none transition-all">

                <button
                    id="clearSearch"
                    type="button"
                    class="hidden absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white/10 text-zinc-400 hover:text-white hover:bg-white/20 transition flex items-center justify-center"
                    aria-label="Hapus pencarian">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            {{-- GROUP FILTER --}}
            <div class="relative lg:w-64">
                <select
                    id="g"
                    class="w-full appearance-none bg-[#121218] border border-[#1f1f27] rounded-2xl px-4 py-4 pr-10 text-sm text-zinc-300 outline-none focus:border-[#d946ef]/50 transition cursor-pointer">
                    <option value="">Semua Grup</option>
                    @foreach($groups as $group)
                        @php $groupName = Sx::get($group, 'nama', 'name'); @endphp
                        @if($groupName)
                            <option value="{{ strtolower($groupName) }}">{{ $groupName }}</option>
                        @endif
                    @endforeach
                </select>

                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 pointer-events-none">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </div>

        </div>

        {{-- FILTER INFO --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
            <p class="text-xs text-zinc-500">
                Menampilkan <span id="resultCount" class="text-white font-bold">{{ $totalIdols }}</span> idol
            </p>
            <button id="resetFilter" type="button" class="text-xs text-[#d946ef] hover:text-white transition font-semibold">
                Reset Filter ↺
            </button>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- IDOL GRID --}}
    {{-- ============================================================ --}}
    <div id="grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">

        @foreach($idols as $index => $i)
            @php
                $n      = Sx::get($i, 'nama', 'name') ?? 'Idol';
                $gn     = Sx::groupName($i) ?? '';
                $foto   = Sx::get($i, 'foto', 'image', 'avatar') ?: Sx::ph($n);
                $posisi = Sx::get($i, 'posisi', 'position');
                $foll   = Sx::get($i, 'followers');
                $id     = $i['id'] ?? 0;
            @endphp

            <a href="{{ route('idols.show', $id) }}"
               data-n="{{ strtolower($n . ' ' . $gn) }}"
               data-g="{{ strtolower($gn) }}"
               class="item idol-card card-stagger group relative rounded-2xl overflow-hidden aspect-[3/4] border border-[#1f1f27] hover:border-[#d946ef]/50 bg-[#121218]"
               style="animation-delay: {{ min($index * 0.05, 1) }}s;">

                {{-- IMAGE --}}
                <img
                    src="{{ $foto }}"
                    alt="{{ $n }}"
                    loading="lazy"
                    referrerpolicy="no-referrer"
                    onerror="this.onerror=null;this.src='{{ Sx::ph($n) }}'"
                    class="card-image absolute inset-0 w-full h-full object-cover object-top">

                {{-- GRADIENT OVERLAY --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-90"></div>

                {{-- GROUP BADGE --}}
                @if($gn)
                    <div class="absolute top-3 left-3">
                        <span class="bg-black/60 backdrop-blur-md border border-white/10 text-white text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-lg">
                            {{ $gn }}
                        </span>
                    </div>
                @endif

                {{-- FOLLOWERS --}}
                @if($foll)
                    <div class="absolute top-3 right-3">
                        <span class="flex items-center gap-1 bg-black/60 backdrop-blur-md border border-white/10 text-white text-[10px] font-semibold px-2 py-1 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="currentColor" class="text-[#d946ef]">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            {{ $foll }}
                        </span>
                    </div>
                @endif

                {{-- INFO --}}
                <div class="absolute bottom-0 left-0 right-0 p-4">

                    @if($gn)
                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#d946ef] mb-0.5">{{ $gn }}</p>
                    @endif

                    <h3 class="text-base md:text-lg font-display font-bold text-white leading-tight truncate">{{ $n }}</h3>

                    @if($posisi)
                        <p class="text-[11px] text-zinc-400 truncate mt-0.5">{{ $posisi }}</p>
                    @endif

                </div>

                {{-- HOVER OVERLAY --}}
                <div class="absolute inset-0 bg-gradient-to-t from-[#d946ef]/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-20">
                    <span class="bg-white text-black text-xs font-bold px-4 py-2 rounded-full flex items-center gap-1.5 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                        Lihat Profil
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                </div>

            </a>
        @endforeach

    </div>

    {{-- ============================================================ --}}
    {{-- EMPTY STATE --}}
    {{-- ============================================================ --}}
    <div id="empty" class="hidden py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-[#121218] border border-[#1f1f27] flex items-center justify-center mx-auto mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-zinc-500">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Idol Tidak Ditemukan</h3>
        <p class="text-sm text-zinc-500 mb-5">Coba gunakan kata kunci atau filter grup yang berbeda.</p>
        <button id="emptyReset" type="button" class="bg-gradient-to-r from-[#d946ef] to-[#06b6d4] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:opacity-90 transition">
            Reset Pencarian
        </button>
    </div>

</div>

{{-- ============================================================
     SCRIPTS
     ============================================================ --}}
<script>
(function() {
    'use strict';

    /* ============================================================
       1. PARTICLE BACKGROUND
       ============================================================ */
    const canvas = document.getElementById('idols-particles');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 1.5 + 0.5;
                this.speedX = (Math.random() - 0.5) * 0.3;
                this.speedY = (Math.random() - 0.5) * 0.3;
                this.color = Math.random() > 0.5 ? '217,70,239' : '6,182,212';
                this.opacity = Math.random() * 0.4 + 0.1;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${this.color},${this.opacity})`;
                ctx.fill();
            }
        }

        const count = Math.min(50, Math.floor(window.innerWidth / 30));
        for (let i = 0; i < count; i++) particles.push(new Particle());

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        }
        animate();
    }

    /* ============================================================
       2. 3D TILT EFFECT
       ============================================================ */
    document.querySelectorAll('.idol-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * -8;
            const rotateY = ((x - centerX) / centerX) * 8;
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px) scale(1.02)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    /* ============================================================
       3. FILTER (SEARCH + GROUP)
       ============================================================ */
    const search      = document.getElementById('q');
    const group       = document.getElementById('g');
    const clear       = document.getElementById('clearSearch');
    const reset       = document.getElementById('resetFilter');
    const emptyReset  = document.getElementById('emptyReset');
    const empty       = document.getElementById('empty');
    const resultCount = document.getElementById('resultCount');
    const items       = document.querySelectorAll('.item');

    function filterIdols() {
        const keyword = search.value.toLowerCase().trim();
        const selectedGroup = group.value.toLowerCase();
        let count = 0;

        items.forEach(item => {
            const name = item.dataset.n || '';
            const idolGroup = item.dataset.g || '';
            const matchName = name.includes(keyword);
            const matchGroup = !selectedGroup || idolGroup === selectedGroup;
            const visible = matchName && matchGroup;

            item.classList.toggle('hidden', !visible);
            if (visible) count++;
        });

        resultCount.textContent = count;
        empty.classList.toggle('hidden', count !== 0);
        clear.classList.toggle('hidden', search.value.length === 0);
    }

    function resetFilters() {
        search.value = '';
        group.value = '';
        filterIdols();
    }

    if (search) search.addEventListener('input', filterIdols);
    if (group) group.addEventListener('change', filterIdols);
    if (clear) clear.addEventListener('click', () => { search.value = ''; search.focus(); filterIdols(); });
    if (reset) reset.addEventListener('click', resetFilters);
    if (emptyReset) emptyReset.addEventListener('click', resetFilters);

    /* ============================================================
       4. COUNT-UP ANIMATION
       ============================================================ */
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count) || 0;
        const duration = 1500;
        const start = performance.now();

        function update(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(target * eased);
            if (progress < 1) requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    });

})();
</script>

@endsection