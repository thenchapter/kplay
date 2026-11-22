@extends('layouts.app')

@php
    use App\Support\Sx;

    $groups      = $groups ?? [];
    $idols       = $idols ?? [];
    $followedIds = $followedIds ?? [];
@endphp

@section('title', 'Groups')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap');

    .font-display,
    .hero-title,
    .group-name,
    .stat-number {
        font-family: 'Space Grotesk', 'Outfit', 'Inter', sans-serif !important;
        letter-spacing: -0.03em;
    }
    .font-body, body {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif !important;
    }
    .font-mono,
    .label-mono,
    .stat-label,
    .group-meta,
    .badge-genre,
    .badge-member {
        font-family: 'JetBrains Mono', 'SF Mono', 'Consolas', monospace !important;
    }

    .groups-bg {
        position: fixed;
        inset: 0;
        z-index: -3;
        background: linear-gradient(135deg, #0a0a0f 0%, #1a0f2e 50%, #0a1a1f 100%);
        background-size: 400% 400%;
        animation: gradientMove 18s ease infinite;
    }
    @keyframes gradientMove {
        0%, 100% { background-position: 0% 50%; }
        50%      { background-position: 100% 50%; }
    }

    .orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(100px);
        z-index: -2;
        pointer-events: none;
        opacity: .35;
    }
    .orb-1 { width: 500px; height: 500px; background: #d946ef; top: -15%; left: -15%; animation: floatOrb1 22s ease-in-out infinite; }
    .orb-2 { width: 500px; height: 500px; background: #06b6d4; bottom: -15%; right: -15%; animation: floatOrb2 28s ease-in-out infinite; }
    .orb-3 { width: 350px; height: 350px; background: #8b5cf6; top: 45%; left: 50%; animation: floatOrb3 20s ease-in-out infinite; }
    @keyframes floatOrb1 { 0%,100%{transform:translate(0,0) scale(1);} 33%{transform:translate(100px,80px) scale(1.15);} 66%{transform:translate(-60px,100px) scale(.9);} }
    @keyframes floatOrb2 { 0%,100%{transform:translate(0,0) scale(1);} 33%{transform:translate(-100px,-80px) scale(1.15);} 66%{transform:translate(80px,-100px) scale(.9);} }
    @keyframes floatOrb3 { 0%,100%{transform:translate(0,0) scale(1);} 50%{transform:translate(-120px,120px) scale(1.2);} }

    #groups-particles { position: fixed; inset: 0; z-index: -1; pointer-events: none; opacity: .7; }

    /* ============================================================
       GROUP CARD — BISA DIKLIK
       ============================================================ */
    .group-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: transform .5s cubic-bezier(.25,.46,.45,.94);
        text-decoration: none;
        color: inherit;
    }

    .group-card:hover {
        transform: translateY(-8px);
    }

    .avatar-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .orbit-ring {
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        background: conic-gradient(from 0deg, #d946ef, transparent 30%, #06b6d4, transparent 60%, #8b5cf6, transparent 90%, #d946ef);
        animation: orbitSpin 6s linear infinite;
        opacity: 0;
        transition: opacity .5s;
    }
    .group-card:hover .orbit-ring { opacity: 1; }
    @keyframes orbitSpin { to { transform: rotate(360deg); } }

    .avatar-glow {
        position: absolute;
        inset: -20px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(217,70,239,.4) 0%, transparent 70%);
        animation: glowPulse 3s ease-in-out infinite;
        z-index: -1;
    }
    @keyframes glowPulse { 0%,100%{opacity:.4;transform:scale(1);} 50%{opacity:.7;transform:scale(1.1);} }

    .avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        box-shadow: 0 0 0 4px rgba(10,10,15,1), 0 0 0 5px rgba(217,70,239,.3);
        transition: box-shadow .5s, transform .5s;
        z-index: 1;
    }
    .group-card:hover .avatar-img {
        box-shadow: 0 0 0 4px rgba(10,10,15,1), 0 0 0 5px rgba(6,182,212,.8);
        transform: scale(1.05);
    }

    .avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform .8s cubic-bezier(.25,.46,.45,.94);
    }
    .group-card:hover .avatar-img img { transform: scale(1.15); }

    .badge-genre {
        position: absolute;
        top: -4px;
        right: -4px;
        z-index: 5;
        background: linear-gradient(135deg, #d946ef, #8b5cf6);
        color: #fff;
        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 3px 8px;
        border-radius: 999px;
        box-shadow: 0 2px 10px rgba(217,70,239,.5);
        transform: scale(0);
        transition: transform .4s cubic-bezier(.34,1.56,.64,1);
    }
    .group-card:hover .badge-genre { transform: scale(1); }

    .badge-member {
        position: absolute;
        bottom: -4px;
        left: -4px;
        z-index: 5;
        background: linear-gradient(135deg, #06b6d4, #8b5cf6);
        color: #fff;
        font-size: 9px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 999px;
        box-shadow: 0 2px 10px rgba(6,182,212,.5);
        display: flex;
        align-items: center;
        gap: 3px;
    }

    /* ============================================================
       INFO TEXT
       ============================================================ */
    .group-name {
        margin-top: 18px;
        font-weight: 700;
        font-size: 18px;
        letter-spacing: -0.03em;
        color: #ffffff;
        transition: color .3s, letter-spacing .3s;
        line-height: 1.2;
    }
    .group-card:hover .group-name { color: #d946ef; letter-spacing: -0.01em; }

    .group-meta {
        font-size: 12px;
        color: #a1a1aa;
        margin-top: 6px;
        letter-spacing: 0.02em;
        transition: color .3s;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .group-card:hover .group-meta { color: #ffffff; }

    .meta-dot    { color: #d946ef; font-weight: 700; }
    .meta-agency { color: #e4e4e7; font-weight: 600; }
    .meta-year   { color: #06b6d4; font-weight: 600; }
    .group-card:hover .meta-agency,
    .group-card:hover .meta-year { color: #ffffff; }

    /* ============================================================
       FOLLOW BUTTON
       ============================================================ */
    .follow-btn {
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 999px;
        transition: all .3s cubic-bezier(.25,.46,.45,.94);
        cursor: pointer;
        border: 1px solid transparent;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .follow-btn.follow {
        background: linear-gradient(135deg, #d946ef, #8b5cf6);
        color: #fff;
        box-shadow: 0 4px 15px rgba(217,70,239,.4);
    }

    .follow-btn.follow:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(217,70,239,.6);
    }

    .follow-btn.following {
        background: transparent;
        color: #a1a1aa;
        border-color: rgba(255,255,255,.15);
    }

    .follow-btn.following:hover {
        background: rgba(239,68,68,.1);
        color: #f87171;
        border-color: rgba(239,68,68,.3);
    }

    /* ============================================================
       MEMBER AVATARS
       ============================================================ */
    .member-avatar {
        transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s, z-index .3s;
    }
    .member-avatar:hover {
        transform: translateY(-6px) scale(1.25);
        z-index: 20;
        box-shadow: 0 8px 20px rgba(217,70,239,.6);
    }

    /* ============================================================
       ANIMATIONS
       ============================================================ */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px) scale(.9); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .stagger { opacity: 0; animation: fadeInUp .8s cubic-bezier(.25,.46,.45,.94) forwards; }

    .reveal { opacity: 0; transform: translateY(30px); transition: opacity .8s ease, transform .8s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    @keyframes floatSoft { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-6px);} }
    .float-soft { animation: floatSoft 4s ease-in-out infinite; }

    .gradient-text {
        background: linear-gradient(135deg, #d946ef, #8b5cf6, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        background-size: 200% 200%;
        animation: gradientMove 4s ease infinite;
    }

    .hero-title {
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 0.95;
        text-shadow: 0 0 60px rgba(217,70,239,.3);
    }

    .stat-number {
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1;
        background: linear-gradient(135deg, #fff, #a1a1aa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-size: 9px;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #52525b;
    }

    .label-mono {
        font-size: 10px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        font-weight: 500;
    }
</style>

{{-- BACKGROUND --}}
<div class="groups-bg"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>
<canvas id="groups-particles"></canvas>

<div class="max-w-7xl mx-auto relative font-body">

    {{-- HERO --}}
    <div class="relative mb-16 pt-4 reveal">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-[#d946ef] animate-pulse"></span>
                    <span class="label-mono font-mono text-[#d946ef]">K-PLAY Groups</span>
                </div>
                <h1 class="hero-title font-display text-5xl md:text-7xl text-white">
                    Explore<br>
                    <span class="gradient-text">Groups.</span>
                </h1>
                <p class="text-sm text-zinc-400 mt-5 max-w-lg leading-relaxed font-body">
                    Jelajahi berbagai grup K-Pop, kenali member, dan temukan favoritmu.
                </p>
            </div>
            <div class="flex gap-8">
                <div class="text-center float-soft">
                    <p class="stat-number font-display text-5xl">{{ count($groups) }}</p>
                    <p class="stat-label font-mono mt-2">Groups</p>
                </div>
                <div class="text-center float-soft" style="animation-delay:.5s">
                    <p class="stat-number font-display text-5xl">{{ count($idols) }}</p>
                    <p class="stat-label font-mono mt-2">Idols</p>
                </div>
            </div>
        </div>
    </div>

    {{-- GRID --}}
    @if(count($groups) > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-14 pb-20">

            @foreach($groups as $index => $g)
                @php
                    $n      = Sx::get($g, 'nama', 'name') ?? 'Grup';
                    $gId    = $g['id'] ?? null;
                    $logo   = Sx::get($g, 'logo', 'foto', 'image');
                    $agensi = Sx::get($g, 'agensi', 'agency');
                    $debut  = Sx::get($g, 'debut');
                    $isFollowing = in_array((string) $gId, array_map('strval', $followedIds), true);

                    $mem = array_filter($idols, function ($i) use ($n, $gId) {
                        $iGroupName = strtolower(Sx::groupName($i) ?? '');
                        $matchByName = $iGroupName === strtolower($n);
                        $iGroupId = $i['group_id'] ?? null;
                        $matchById = ($iGroupId !== null && $gId !== null && $iGroupId == $gId);
                        return $matchByName || $matchById;
                    });
                @endphp

                <div class="group-card stagger reveal"
                     style="animation-delay: {{ min($index * 0.06, 1) }}s;">

                    <div class="avatar-wrapper">
                        <div class="avatar-glow"></div>
                        <div class="orbit-ring"></div>

                        @if(Sx::get($g, 'genre'))
                            <span class="badge-genre font-mono">{{ Sx::get($g, 'genre') }}</span>
                        @endif

                        @if(Sx::get($g, 'member') || Sx::get($g, 'member_count'))
                            <span class="badge-member font-mono">
                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                </svg>
                                {{ Sx::get($g, 'member') ?? Sx::get($g, 'member_count') }}
                            </span>
                        @endif

                        <div class="avatar-img">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $n }}" loading="lazy" referrerpolicy="no-referrer"
                                     onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-logo').style.display='flex';">
                            @endif
                            <div class="fallback-logo {{ $logo ? 'hidden' : 'flex' }} absolute inset-0 items-center justify-center bg-gradient-to-br from-[#1a1228] to-[#0f1a1f]">
                                <span class="text-3xl font-display gradient-text">{{ strtoupper(substr($n, 0, 2)) }}</span>
                            </div>
                        </div>
                    </div>

                    <h3 class="group-name font-display">{{ $n }}</h3>

                    <p class="group-meta font-mono">
                        @if($agensi)<span class="meta-agency">{{ $agensi }}</span>@endif
                        @if($agensi && $debut)<span class="meta-dot">·</span>@endif
                        @if($debut)<span class="meta-year">{{ $debut }}</span>@endif
                    </p>

                    {{-- FOLLOW BUTTON --}}
                    @if(session('user'))
                        <form action="{{ $isFollowing ? route('groups.unfollow') : route('groups.follow') }}"
                              method="POST"
                              class="mt-3">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $gId }}">
                            <button type="submit"
                                    class="follow-btn {{ $isFollowing ? 'following' : 'follow' }} font-mono">
                                @if($isFollowing)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                    Following
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v14M5 12h14"/>
                                    </svg>
                                    Follow
                                @endif
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="follow-btn follow font-mono mt-3">
                            Login untuk Follow
                        </a>
                    @endif

                    {{-- MEMBER AVATARS --}}
                    @if(count($mem) > 0)
                        <div class="flex items-center justify-center gap-1.5 mt-3">
                            <div class="flex -space-x-1.5">
                                @foreach(array_slice($mem, 0, 4) as $m)
                                    @php
                                        $mn  = Sx::get($m, 'nama', 'name') ?? 'M';
                                        $mId = $m['id'] ?? 0;
                                        $mF  = Sx::get($m, 'foto', 'image') ?: Sx::ph($mn);
                                    @endphp
                                    <a href="{{ route('idols.show', $mId) }}" title="{{ $mn }}"
                                       class="member-avatar w-6 h-6 rounded-full overflow-hidden ring-2 ring-[#0a0a0f]">
                                        <img src="{{ $mF }}" alt="{{ $mn }}" loading="lazy" referrerpolicy="no-referrer"
                                             onerror="this.onerror=null;this.src='{{ Sx::ph($mn) }}'"
                                             class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                                @if(count($mem) > 4)
                                    <div class="w-6 h-6 rounded-full ring-2 ring-[#0a0a0f] bg-gradient-to-br from-[#d946ef] to-[#06b6d4] flex items-center justify-center text-[8px] font-mono font-bold text-white">
                                        +{{ count($mem) - 4 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach

        </div>
    @else
        <div class="text-center py-24 reveal">
            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#d946ef]/20 to-[#06b6d4]/20 border border-white/10 flex items-center justify-center mx-auto mb-6 float-soft">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-[#d946ef]">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h3 class="font-display text-xl text-white mb-2">Belum Ada Grup</h3>
            <p class="text-sm text-zinc-500 font-body">Data grup K-Pop belum tersedia saat ini.</p>
        </div>
    @endif

</div>

<script>
(function() {
    'use strict';

    /* ============================================================
       PARTICLE CONSTELLATION
       ============================================================ */
    const canvas = document.getElementById('groups-particles');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        let mouse = { x: null, y: null, radius: 180 };

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
        window.addEventListener('mouseleave', () => {
            mouse.x = null;
            mouse.y = null;
        });

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2.5 + 0.8;
                this.speedX = (Math.random() - 0.5) * 0.5;
                this.speedY = (Math.random() - 0.5) * 0.5;
                this.color = Math.random() > 0.5 ? '217,70,239' : (Math.random() > 0.5 ? '6,182,212' : '139,92,246');
                this.opacity = Math.random() * 0.6 + 0.3;
                this.pulse = Math.random() * Math.PI * 2;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                this.pulse += 0.02;
                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;
                if (mouse.x !== null) {
                    const dx = this.x - mouse.x;
                    const dy = this.y - mouse.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < mouse.radius) {
                        const force = (mouse.radius - dist) / mouse.radius;
                        this.x += (dx / dist) * force * 3;
                        this.y += (dy / dist) * force * 3;
                    }
                }
            }
            draw() {
                const pulseSize = this.size + Math.sin(this.pulse) * 0.5;
                ctx.beginPath();
                ctx.arc(this.x, this.y, pulseSize, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${this.color},${this.opacity})`;
                ctx.fill();
                ctx.beginPath();
                ctx.arc(this.x, this.y, pulseSize * 2, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${this.color},${this.opacity * 0.15})`;
                ctx.fill();
            }
        }

        const count = Math.min(80, Math.floor(window.innerWidth / 20));
        for (let i = 0; i < count; i++) particles.push(new Particle());

        function connectParticles() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 140) {
                        const opacity = 0.2 * (1 - dist / 140);
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(217,70,239,${opacity})`;
                        ctx.lineWidth = 0.6;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
                if (mouse.x !== null) {
                    const dx = particles[i].x - mouse.x;
                    const dy = particles[i].y - mouse.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 180) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(6,182,212,${0.3 * (1 - dist / 180)})`;
                        ctx.lineWidth = 0.8;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(mouse.x, mouse.y);
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

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelector