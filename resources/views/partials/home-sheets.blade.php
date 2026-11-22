{{-- Taruh di home.blade.php (sebelum </main>): @include('partials.home-sheets') --}}

@php
    use Carbon\Carbon;
    
    // Panggil Service Google Sheets via Container
    $gs = app(\App\Services\GoogleSheetService::class);
    
    // Ambil data (dengan try-catch agar jika sheets error, web tidak crash)
    try {
        $allIdols     = $gs->getAll('idols') ?: [];
        $allGroups    = $gs->getAll('groups') ?: [];
        $allNews      = $gs->getAll('news') ?: [];
        $allComebacks = $gs->getAll('comebacks') ?: [];
        $allPosts     = $gs->getAll('posts') ?: [];
    } catch (\Throwable $e) {
        $allIdols = $allGroups = $allNews = $allComebacks = $allPosts = [];
    }

    // Slice & sort data
    $hIdols  = array_slice($allIdols, 0, 6);
    $hGroups = array_slice($allGroups, 0, 4);

    $hNews = collect($allNews)
        ->sortByDesc('tanggal')
        ->take(3);

    $hComes = collect($allComebacks)
        ->filter(fn($c) => !empty($c['tanggal']) && Carbon::parse($c['tanggal'])->isFuture())
        ->sortBy('tanggal')
        ->take(4);

    $hPosts  = collect($allPosts)->sortByDesc('created_at')->take(3);

    // Helper section header
    $sec = function($title, $url) {
        return '<div class="flex justify-between items-end mb-5 mt-16">'
            . '<h2 class="text-2xl font-display font-extrabold">' . $title . '</h2>'
            . '<a href="' . $url . '" class="text-sm font-bold text-[#d946ef] hover:text-white transition">Lihat semua →</a>'
            . '</div>';
    };
@endphp

<section class="mt-20">

    {{-- ================= TRENDING IDOLS ================= --}}
    {!! $sec('🔥 Trending Idols', route('idols.index')) !!}
    @if(count($hIdols) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($hIdols as $i)
                @php
                    $n  = $i['nama'] ?? 'Idol';
                    $id = $i['id'] ?? 0;
                    $gn = $i['grup'] ?? '';
                    $foto = $i['foto'] ?? 'https://ui-avatars.com/api/?size=200&background=d946ef&color=fff&name='.urlencode($n);
                @endphp
                <a href="{{ route('idols.show', $id) }}" class="group text-center">
                    <img src="{{ $foto }}" alt="{{ $n }}"
                        class="w-full aspect-square rounded-2xl object-cover border border-[#1f1f27] group-hover:border-[#d946ef] transition">
                    <p class="font-bold text-sm mt-2">{{ $n }}</p>
                    <p class="text-xs text-zinc-500">{{ $gn }}</p>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-zinc-500 text-sm">Belum ada data idol.</p>
    @endif

    {{-- ================= GRUP REKOMENDASI ================= --}}
    {!! $sec('⭐ Grup Rekomendasi', route('groups.index')) !!}
    @if(count($hGroups) > 0)
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($hGroups as $g)
                @php
                    $n = $g['nama'] ?? 'Grup';
                    $logo = $g['logo'] ?? 'https://ui-avatars.com/api/?size=200&background=06b6d4&color=fff&name='.urlencode($n);
                @endphp
                <a href="{{ route('groups.index') }}" class="relative rounded-2xl overflow-hidden aspect-[4/3] border border-[#1f1f27] group">
                    <img src="{{ $logo }}" alt="{{ $n }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 to-transparent"></div>
                    <div class="absolute bottom-0 p-4">
                        <h3 class="font-display font-bold text-lg">{{ $n }}</h3>
                        <p class="text-xs text-zinc-300">{{ $g['agensi'] ?? '' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-zinc-500 text-sm">Belum ada data grup.</p>
    @endif

    {{-- ================= COMEBACK TERDEKAT ================= --}}
    {!! $sec('📅 Comeback Terdekat', route('news.calendar')) !!}
    @if($hComes->count() > 0)
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($hComes as $c)
                @php
                    try {
                        $d = Carbon::parse($c['tanggal']);
                    } catch (\Throwable $e) { continue; }
                @endphp
                <div class="flex gap-4 bg-[#121218] border border-[#1f1f27] rounded-2xl p-4">
                    <div class="w-16 text-center rounded-xl bg-[#d946ef]/20 py-2">
                        <p class="text-2xl font-extrabold leading-none text-[#d946ef]">{{ $d->format('d') }}</p>
                        <p class="text-[11px] uppercase text-[#d946ef]">{{ $d->translatedFormat('M') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#d946ef] uppercase">
                            {{ $c['grup'] ?? $c['artis'] ?? '' }}
                        </p>
                        <h3 class="font-bold">{{ $c['judul'] ?? $c['album'] ?? '' }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-zinc-500 text-sm">Belum ada jadwal comeback.</p>
    @endif

    {{-- ================= BERITA TERBARU ================= --}}
    {!! $sec('📰 Berita Terbaru', route('news.index')) !!}
    @if($hNews->count() > 0)
        <div class="grid md:grid-cols-3 gap-5">
            @foreach($hNews as $n)
                @php
                    $t  = $n['judul'] ?? 'Berita';
                    $id = $n['id'] ?? 0;
                    $img = $n['image'] ?? 'https://ui-avatars.com/api/?size=400&background=7c3aed&color=fff&name='.urlencode($t);
                @endphp
                <a href="{{ route('news.index') }}" class="bg-[#121218] border border-[#1f1f27] rounded-2xl overflow-hidden hover:border-[#d946ef66] transition">
                    <img src="{{ $img }}" alt="{{ $t }}" class="h-40 w-full object-cover">
                    <div class="p-4">
                        <h3 class="font-bold line-clamp-2">{{ $t }}</h3>
                        <p class="text-xs text-zinc-500 mt-1">{{ $n['tanggal'] ?? '' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-zinc-500 text-sm">Belum ada berita.</p>
    @endif

    {{-- ================= DISKUSI KOMUNITAS ================= --}}
    {!! $sec('💬 Diskusi Komunitas', route('community.index')) !!}
    @if($hPosts->count() > 0)
        <div class="grid md:grid-cols-3 gap-5">
            @foreach($hPosts as $p)
                @php
                    // Cari nama user dari data user_id
                    $user = collect($gs->getAll('users'))->firstWhere('id', $p['user_id'] ?? null);
                    $auName = $user['nama'] ?? 'Anggota K-PLAY';
                    $pId = $p['id'] ?? 0;
                @endphp
                <a href="{{ route('community.index') }}" class="bg-[#121218] border border-[#1f1f27] rounded-2xl p-5 hover:border-[#d946ef66] transition">
                    <p class="text-xs text-zinc-500 mb-2">
                        {{ $auName }} · {{ \Carbon\Carbon::parse($p['created_at'])->diffForHumans() }}
                    </p>
                    <h3 class="font-bold line-clamp-2">{{ $p['isi'] ?? '' }}</h3>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-zinc-500 text-sm">Belum ada diskusi.</p>
    @endif

</section>