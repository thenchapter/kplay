<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Profil') | K-PLAY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {
                fontFamily: { sans: ['Inter','sans-serif'], display: ['Outfit','sans-serif'] },
                colors: {
                    bg: '#0a0a0f',
                    surface: '#121218',
                    border: '#1f1f27',
                    accent: { DEFAULT: '#d946ef', hover: '#c026d3' },
                    cyan: { DEFAULT: '#06b6d4', hover: '#0891b2' },
                    violet: { DEFAULT: '#8b5cf6', hover: '#7c3aed' }
                },
            } }
        }
    </script>
    <style>
        * { -webkit-tap-highlight-color: transparent; }

        body {
            background: #0a0a0f;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ============================================================
           PARTICLE BACKGROUND
           ============================================================ */
        #particles-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
        }

        /* ============================================================
           AURORA GLOW
           ============================================================ */
        .aurora {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            pointer-events: none;
            animation: auroraMove 20s ease-in-out infinite;
        }
        .aurora-1 { top: -15%; left: -15%; width: 60vw; height: 60vw; background: #d946ef; opacity: .2; }
        .aurora-2 { bottom: -15%; right: -15%; width: 60vw; height: 60vw; background: #06b6d4; opacity: .2; animation-delay: -7s; }
        .aurora-3 { top: 30%; left: 40%; width: 40vw; height: 40vw; background: #8b5cf6; opacity: .12; animation-delay: -14s; }

        @keyframes auroraMove {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(60px, -40px) scale(1.1); }
            66%      { transform: translate(-40px, 60px) scale(.9); }
        }

        /* ============================================================
           AVATAR RING BERPUTAR + ORBIT
           ============================================================ */
        .avatar-ring {
            position: relative;
            display: inline-block;
            padding: 4px;
            border-radius: 9999px;
            background: conic-gradient(from 0deg, #d946ef, #8b5cf6, #06b6d4, #d946ef);
            animation: spin 5s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .avatar-ring::before {
            content: '';
            position: absolute;
            inset: -20px;
            border-radius: 9999px;
            background: radial-gradient(circle, rgba(217,70,239,.3) 0%, transparent 70%);
            animation: pulseGlow 3s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: .3; transform: scale(1); }
            50%      { opacity: .6; transform: scale(1.1); }
        }

        /* Orbit dots */
        .orbit-dot {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #06b6d4;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            margin: -3px 0 0 -3px;
            transform-origin: 0 0;
            box-shadow: 0 0 10px #06b6d4, 0 0 20px #06b6d4;
        }
        .orbit-dot:nth-child(1) { animation: orbit1 4s linear infinite; }
        .orbit-dot:nth-child(2) { animation: orbit2 4s linear infinite; background: #d946ef; box-shadow: 0 0 10px #d946ef, 0 0 20px #d946ef; }

        @keyframes orbit1 {
            from { transform: rotate(0deg) translateX(70px) rotate(0deg); }
            to   { transform: rotate(360deg) translateX(70px) rotate(-360deg); }
        }
        @keyframes orbit2 {
            from { transform: rotate(180deg) translateX(80px) rotate(-180deg); }
            to   { transform: rotate(540deg) translateX(80px) rotate(-540deg); }
        }

        /* ============================================================
           TYPING EFFECT
           ============================================================ */
        .typing {
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid #d946ef;
            animation: typing 2s steps(30, end), blink .7s step-end infinite;
            display: inline-block;
            max-width: 100%;
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink {
            50% { border-color: transparent; }
        }

        /* ============================================================
           TEXT GRADIENT
           ============================================================ */
        .text-gradient {
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
           DIVIDER GRADIENT
           ============================================================ */
        .divider-gradient {
            height: 1px;
            background: linear-gradient(90deg, transparent, #d946ef40, #06b6d440, transparent);
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
           CURSOR GLOW
           ============================================================ */
        #cursor-glow {
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(217,70,239,.15) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            transition: transform .1s ease;
            transform: translate(-50%, -50%);
        }

        /* ============================================================
           MAGNETIC HOVER
           ============================================================ */
        .magnetic {
            transition: transform .3s cubic-bezier(.25,.46,.45,.94);
        }

        /* ============================================================
           SCROLLBAR HIDE
           ============================================================ */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* ============================================================
           TAB ACTIVE GLOW
           ============================================================ */
        .tab-active-glow {
            position: absolute;
            bottom: 0;
            left: 2px;
            right: 2px;
            height: 2px;
            border-radius: 2px;
            background: linear-gradient(90deg, #d946ef, #8b5cf6, #06b6d4);
            box-shadow: 0 0 12px rgba(217,70,239,.8), 0 0 24px rgba(217,70,239,.4);
            animation: tabPulse 2s ease-in-out infinite;
        }

        @keyframes tabPulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 12px rgba(217,70,239,.8), 0 0 24px rgba(217,70,239,.4); }
            50%      { opacity: .7; box-shadow: 0 0 6px rgba(217,70,239,.5), 0 0 12px rgba(217,70,239,.2); }
        }

        /* ============================================================
           BUTTON SHIMMER
           ============================================================ */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
        }
        .btn-shimmer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
            transition: left .6s;
        }
        .btn-shimmer:hover::before {
            left: 100%;
        }

        /* ============================================================
           STAT NUMBER POP
           ============================================================ */
        .stat-number {
            transition: transform .3s cubic-bezier(.25,.46,.45,.94);
        }
        .stat-item:hover .stat-number {
            transform: scale(1.15);
        }

        /* ============================================================
           FLOATING ANIMATION
           ============================================================ */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }
        .float-slow { animation: float 4s ease-in-out infinite; }

        /* ============================================================
           RIPPLE EFFECT
           ============================================================ */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.4);
            transform: scale(0);
            animation: rippleAnim .6s linear;
            pointer-events: none;
        }
        @keyframes rippleAnim {
            to { transform: scale(4); opacity: 0; }
        }
    </style>
</head>
<body class="antialiased min-h-screen relative">

@php
    $u    = fn($name, $path) => Route::has($name) ? route($name) : url($path);
    $nama = $user['nama'] ?? $user['name'] ?? ltrim($user['username'] ?? 'Pengguna', '@');
    $av   = $user['avatar'] ?? $user['foto'] ?? 'https://ui-avatars.com/api/?size=300&background=d946ef&color=fff&name=' . urlencode($nama);
    $un   = ltrim($user['username'] ?? '', '@');
    $bio  = $user['bio'] ?? '';

    $tgl = null; $aktif = null;
    try { if (!empty($user['joined_at']))  $tgl   = \Carbon\Carbon::parse($user['joined_at'])->translatedFormat('d M Y'); } catch (\Throwable $e) {}
    try { if (!empty($user['last_login'])) $aktif = \Carbon\Carbon::parse($user['last_login'])->diffForHumans(); } catch (\Throwable $e) {}

    if (str_starts_with($av, 'http')) {
        $avatarUrl = $av;
    } else {
        $avatarUrl = asset('storage/' . ltrim($av, '/'));
    }

    $tabs = [
        ['Profil',     'profile.index',         '/profile'],
        ['Bias',       'profile.bias',          '/profile/bias'],
        ['Grup',       'profile.groups',        '/profile/groups'],
        ['Playlist',   'profile.playlist',      '/profile/playlist'],
        ['Notifikasi', 'profile.notifications', '/profile/notifications'],
    ];
@endphp

{{-- Cursor glow --}}
<div id="cursor-glow"></div>

{{-- Particle background --}}
<canvas id="particles-bg"></canvas>

{{-- Aurora glows --}}
<div class="aurora aurora-1"></div>
<div class="aurora aurora-2"></div>
<div class="aurora aurora-3"></div>

{{-- ================= NAVBAR ================= --}}
<nav class="fixed top-0 w-full z-50 bg-bg/70 backdrop-blur-xl border-b border-white/5">
    <div class="max-w-6xl mx-auto px-4 md:px-8 h-16 flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-2xl font-display font-black tracking-tight">
            K-PLAY<span class="text-accent text-3xl leading-none">.</span>
        </a>
        <div class="hidden md:flex gap-8 text-sm font-semibold text-zinc-400">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
            <a href="{{ url('/idols') }}" class="hover:text-white transition-colors">Idols</a>
            <a href="{{ url('/groups') }}" class="hover:text-white transition-colors">Groups</a>
            <a href="{{ $u('community.index','/community') }}" class="hover:text-white transition-colors">Community</a>
        </div>
        @include('partials.user-menu')
    </div>
</nav>

<main class="max-w-2xl mx-auto px-4 pt-20 pb-16 relative z-10">

    {{-- FLASH --}}
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/40 text-red-300 text-sm p-3 rounded-xl mb-4">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 text-sm p-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    {{-- ================= HEADER PROFIL ================= --}}
    <section class="flex flex-col items-center text-center pt-6 pb-6 reveal">

        {{-- AVATAR dengan ring + orbit --}}
        <div class="relative group mb-6">
            {{-- Orbit dots --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="orbit-dot"></div>
                <div class="orbit-dot"></div>
            </div>

            {{-- Ring gradient berputar --}}
            <div class="avatar-ring relative">
                <img src="{{ $avatarUrl }}"
                     alt="{{ $nama }}"
                     referrerpolicy="no-referrer"
                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?size=300&background=d946ef&color=fff&name={{ urlencode($nama) }}'"
                     class="w-24 h-24 rounded-full object-cover ring-4 ring-bg relative z-10">
            </div>

            {{-- Tombol ganti foto --}}
            <label for="photoInput"
                   class="absolute inset-0 rounded-full bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer text-white text-[10px] font-bold z-20">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>
                </svg>
                Ganti
            </label>

            <form action="{{ $u('profile.photo','/profile/photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                @csrf
                <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden"
                       onchange="document.getElementById('photoForm').submit()">
            </form>

            {{-- Online indicator --}}
            <span class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-emerald-400 border-[3px] border-bg z-20">
                <span class="absolute inset-0 rounded-full bg-emerald-400 animate-ping"></span>
            </span>
        </div>

        {{-- USERNAME dengan typing effect --}}
        <h1 class="text-2xl font-display font-black text-gradient typing">
            @ {{ $un ?: strtolower(str_replace(' ', '', $nama)) }}
        </h1>

        {{-- NAMA --}}
        <p class="text-sm text-zinc-400 mt-1">{{ $nama }}</p>

        {{-- BIO --}}
        @if($bio)
            <p class="text-sm text-zinc-300 mt-3 max-w-sm leading-relaxed">{{ $bio }}</p>
        @endif

        {{-- META BADGES --}}
        <div class="flex flex-wrap gap-2 justify-center mt-4 text-[11px]">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-accent/15 to-cyan/15 border border-accent/30 text-white font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" class="text-accent">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                K-PLAY Member
            </span>

            @if($tgl)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-zinc-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $tgl }}
                </span>
            @endif

            @if($aktif)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Aktif {{ $aktif }}
                </span>
            @endif
        </div>

    </section>

    {{-- ================= DIVIDER ================= --}}
    <div class="divider-gradient"></div>

    {{-- ================= STATS ================= --}}
    <div class="flex items-center justify-center gap-8 sm:gap-12 py-5 reveal">
        @foreach([
            ['followers', 'Followers', $user['followers'] ?? 0],
            ['following', 'Following', $user['following'] ?? 0],
            ['likes',     'Likes',     $user['likes'] ?? 0],
        ] as [$key, $label, $val])
            <div class="text-center stat-item group cursor-default">
                <p class="text-xl font-display font-black text-gradient stat-number" data-count="{{ $val }}">0</p>
                <p class="text-[11px] text-zinc-500 uppercase tracking-wider mt-0.5">{{ $label }}</p>
            </div>
            @if(!$loop->last)
                <div class="w-px h-8 bg-gradient-to-b from-transparent via-border to-transparent"></div>
            @endif
        @endforeach
    </div>

    {{-- ================= DIVIDER ================= --}}
    <div class="divider-gradient"></div>

    {{-- ================= TOMBOL EDIT + LOGOUT ================= --}}
    <div class="py-4 reveal">
        <div class="flex gap-2">
            <a href="{{ $u('profile.index','/profile') }}"
               class="btn-shimmer magnetic group relative flex-1 text-center py-2.5 rounded-lg text-sm font-bold text-white overflow-hidden transition-all hover:scale-[1.02] shadow-lg shadow-accent/20">
                <span class="absolute inset-0 bg-gradient-to-r from-accent via-violet to-cyan"></span>
                <span class="relative">Edit Profil</span>
            </a>

            <form action="{{ $u('logout','/logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit"
                        title="Logout"
                        class="w-11 h-11 rounded-lg bg-surface border border-border flex items-center justify-center text-red-400 hover:bg-red-500/10 hover:border-red-500/30 transition magnetic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- ================= TAB MENU ================= --}}
    <div class="sticky top-16 z-40 bg-bg/95 backdrop-blur-xl -mx-4 px-4 mb-4 reveal">
        <div class="flex overflow-x-auto scrollbar-hide border-b border-white/5">
            @foreach($tabs as [$label, $routeName, $url])
                @php
                    $href = $u($routeName, $url);
                    $isActive = rtrim(url()->current(), '/') === rtrim($href, '/');
                @endphp
                <a href="{{ $href }}"
                   class="magnetic relative flex-1 min-w-[80px] py-3.5 text-sm font-semibold transition text-center
                          {{ $isActive ? 'text-white' : 'text-zinc-500 hover:text-white' }}">
                    {{ $label }}
                    @if($isActive)
                        <span class="tab-active-glow"></span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- ================= KONTEN ================= --}}
    <div class="reveal">
        @yield('content')
    </div>

</main>

{{-- FLASH TOAST --}}
@if(session('success'))
    <div id="flashToast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl shadow-emerald-500/30 flex items-center gap-2 animate-bounce-in">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        {{ session('success') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashToast')?.remove(), 3000);</script>
@endif

@if(session('error'))
    <div id="flashErr" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-2xl shadow-red-500/30 flex items-center gap-2 animate-bounce-in">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        {{ session('error') }}
    </div>
    <script>setTimeout(() => document.getElementById('flashErr')?.remove(), 3000);</script>
@endif

{{-- ============================================================
     SCRIPTS
     ============================================================ --}}
<script>
(function() {
    'use strict';

    /* ============================================================
       1. PARTICLE BACKGROUND
       ============================================================ */
    const canvas = document.getElementById('particles-bg');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        let mouse = { x: null, y: null, radius: 150 };

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.x;
            mouse.y = e.y;
        });

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = (Math.random() - 0.5) * 0.5;
                this.speedY = (Math.random() - 0.5) * 0.5;
                this.color = Math.random() > 0.5 ? '217,70,239' : '6,182,212';
                this.opacity = Math.random() * 0.5 + 0.2;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;

                // Mouse repel
                if (mouse.x !== null) {
                    const dx = this.x - mouse.x;
                    const dy = this.y - mouse.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < mouse.radius) {
                        const force = (mouse.radius - dist) / mouse.radius;
                        this.x += (dx / dist) * force * 2;
                        this.y += (dy / dist) * force * 2;
                    }
                }
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${this.color},${this.opacity})`;
                ctx.fill();
            }
        }

        const particleCount = Math.min(80, Math.floor(window.innerWidth / 20));
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        function connectParticles() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 120) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(217,70,239,${0.1 * (1 - dist / 120)})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            connectParticles();
            requestAnimationFrame(animate);
        }
        animate();
    }

    /* ============================================================
       2. CURSOR GLOW
       ============================================================ */
    const glow = document.getElementById('cursor-glow');
    if (glow && window.innerWidth > 768) {
        let glowX = window.innerWidth / 2;
        let glowY = window.innerHeight / 2;
        let targetX = glowX;
        let targetY = glowY;

        window.addEventListener('mousemove', (e) => {
            targetX = e.clientX;
            targetY = e.clientY;
        });

        function animateGlow() {
            glowX += (targetX - glowX) * 0.1;
            glowY += (targetY - glowY) * 0.1;
            glow.style.left = glowX + 'px';
            glow.style.top = glowY + 'px';
            requestAnimationFrame(animateGlow);
        }
        animateGlow();
    } else if (glow) {
        glow.style.display = 'none';
    }

    /* ============================================================
       3. SCROLL REVEAL
       ============================================================ */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

    /* ============================================================
       4. COUNT-UP ANIMATION
       ============================================================ */
    function animateCount(el) {
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
    }

    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                statObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-count]').forEach((el) => statObserver.observe(el));

    /* ============================================================
       5. MAGNETIC HOVER
       ============================================================ */
    document.querySelectorAll('.magnetic').forEach((el) => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            el.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
        });
        el.addEventListener('mouseleave', () => {
            el.style.transform = 'translate(0, 0)';
        });
    });

    /* ============================================================
       6. RIPPLE EFFECT ON CLICK
       ============================================================ */
    document.querySelectorAll('a, button').forEach((el) => {
        el.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

})();
</script>

</body>
</html>