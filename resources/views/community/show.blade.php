@extends('layouts.app')
@section('title', 'Komunitas ' . $fandom['nama'] . ' — K-PLAY')

@section('content')
<div class="px-4 py-8 grid lg:grid-cols-4 gap-6 animate-fade-up">
    
    <!-- MAIN FEED -->
    <div class="lg:col-span-3 space-y-6">
        <!-- Header Komunitas -->
        <div class="glass rounded-2xl p-6 relative overflow-hidden flex items-center gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-gradient-to-br {{ $fandom['warna'] }} opacity-20 blur-3xl rounded-full"></div>
            
            <!-- PERBAIKAN GAMBAR HEADER KOMUNITAS -->
            @if(!empty($fandom['logo']))
                <img src="{{ $fandom['logo'] }}" alt="{{ $fandom['nama'] }}" 
                     class="w-20 h-20 rounded-2xl object-cover shadow-lg z-10 bg-slate-800" 
                     referrerpolicy="no-referrer" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                
                <div style="display: none;" class="w-20 h-20 rounded-2xl bg-gradient-to-br {{ $fandom['warna'] }} flex items-center justify-center font-display font-bold text-3xl shadow-lg z-10">
                    {{ substr($fandom['nama'], 0, 1) }}
                </div>
            @else
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br {{ $fandom['warna'] }} flex items-center justify-center font-display font-bold text-3xl shadow-lg z-10">
                    {{ substr($fandom['nama'], 0, 1) }}
                </div>
            @endif

            <div class="z-10">
                <h1 class="text-3xl font-display font-bold">{{ $fandom['nama'] }}</h1>
                <p class="text-slate-400 text-sm">{{ $fandom['deskripsi'] }}</p>
                <div class="flex gap-4 mt-2 text-xs font-semibold text-slate-500">
                    <span>👥 {{ number_format($fandom['member_count']) }} Members</span>
                    <span>📝 {{ count($posts) }} Posts</span>
                </div>
            </div>
        </div>

        <!-- Form Post -->
        @if(session('user'))
        <form method="POST" action="{{ route('community.store', $fandom['slug']) }}" class="glass rounded-2xl p-4">
            @csrf
            <textarea name="isi" rows="3" placeholder="Bagikan sesuatu ke komunitas {{ $fandom['nama'] }}..." required
                class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-sm focus:border-neon-pink focus:outline-none transition resize-none"></textarea>
            <div class="flex justify-between items-center mt-3">
                <div class="flex gap-2 text-slate-400">
                    <button type="button" class="p-2 hover:bg-slate-700 rounded-lg transition" title="Tambah Foto/Video">📷</button>
                    <button type="button" class="p-2 hover:bg-slate-700 rounded-lg transition" title="Tambah GIF">GIF</button>
                </div>
                <button type="submit" class="px-6 py-2 rounded-full bg-gradient-to-r from-neon-pink to-neon-violet font-bold text-sm hover:scale-105 transition">
                    Posting
                </button>
            </div>
        </form>
        @else
        <div class="glass rounded-2xl p-6 text-center">
            <p class="text-slate-400 mb-3">Mau ikut berdiskusi di komunitas ini?</p>
            <a href="{{ route('login') }}" class="inline-block px-6 py-2 rounded-full bg-neon-pink text-white font-bold text-sm">Login Dulu</a>
        </div>
        @endif

        <!-- Daftar Postingan -->
        <div class="space-y-4">
            @forelse($posts as $p)
                @php 
                    // Ambil data user dari mapping yang dikirim controller
                    $authorId = $p['user_id'] ?? '';
                    $author = $users[$authorId] ?? null; 
                    $authorName = $author['nama'] ?? 'Anggota K-PLAY';
                    $authorAvatar = $author['avatar'] ?? 'https://i.pravatar.cc/100?u=' . urlencode($authorName);
                    // Format waktu (menggunakan Carbon dari Laravel)
                    $waktu = isset($p['created_at']) ? \Carbon\Carbon::parse($p['created_at'])->diffForHumans() : 'Baru saja';
                @endphp

                <article class="glass rounded-2xl p-5 border border-transparent hover:border-neon-violet/30 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <img src="{{ $authorAvatar }}" class="w-10 h-10 rounded-full ring-2 ring-neon-pink/30 object-cover">
                        <div>
                            <div class="font-bold text-sm">{{ $authorName }}</div>
                            <div class="text-xs text-slate-500">{{ $waktu }}</div>
                        </div>
                    </div>
                    
                    <p class="text-slate-200 mb-3 whitespace-pre-line">{{ $p['isi'] }}</p>
                    
                    {{-- PERBAIKAN GAMBAR POSTINGAN --}}
                    @if(!empty($p['image']))
                        <div class="rounded-xl overflow-hidden mb-3 border border-slate-700/50">
                            <img src="{{ $p['image'] }}" alt="Post Image" 
                                 class="w-full max-h-96 object-cover hover:scale-105 transition duration-500"
                                 referrerpolicy="no-referrer"
                                 onerror="this.style.display='none'; this.parentElement.style.display='none';">
                        </div>
                    @endif

                    <div class="flex gap-6 mt-4 text-sm text-slate-400 border-t border-slate-800/50 pt-3">
                        <button class="flex items-center gap-1 hover:text-neon-pink transition group">
                            <span class="group-hover:scale-125 transition-transform">❤️</span> {{ $p['likes'] ?? 0 }}
                        </button>
                        <button class="flex items-center gap-1 hover:text-neon-cyan transition group">
                            <span class="group-hover:scale-125 transition-transform">💬</span> {{ $p['comments_count'] ?? 0 }}
                        </button>
                        <button class="flex items-center gap-1 hover:text-slate-200 transition group ml-auto">
                            <span class="group-hover:rotate-12 transition-transform">↗</span> Share
                        </button>
                    </div>
                </article>
            @empty
                <div class="text-center py-10 glass rounded-2xl">
                    <p class="text-slate-500">Belum ada postingan di komunitas ini. Jadilah yang pertama! 🚀</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- SIDEBAR -->
    <div class="space-y-4 hidden lg:block">
        <div class="glass rounded-2xl p-5 sticky top-24">
            <h3 class="font-bold mb-3 text-sm uppercase tracking-wider text-slate-400">Aturan Komunitas</h3>
            <ul class="text-xs text-slate-300 space-y-2">
                <li>1. Saling menghormati sesama member.</li>
                <li>2. Dilarang membagikan konten ilegal/SARA.</li>
                <li>3. Tetap fokus pada topik K-Pop & grup terkait.</li>
                <li>4. No spam & no hate speech.</li>
            </ul>
            <button class="w-full mt-4 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs font-bold hover:bg-red-500/20 transition">
                Leave Community
            </button>
        </div>
    </div>
</div>
@endsection