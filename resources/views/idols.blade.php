<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-PLAY | Idol Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Outfit', 'sans-serif'] },
                    colors: {
                        bg: '#0a0a0f', surface: '#121218', border: '#1f1f27',
                        accent: { DEFAULT: '#d946ef', hover: '#c026d3' },
                        cyan: { DEFAULT: '#06b6d4', hover: '#0891b2' }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out forwards',
                        'slide-up': 'slideUp 0.6s ease-out forwards'
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0a0a0f; color: #f8fafc; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Efek Latar Belakang Glow */
        .bg-glow { position: fixed; width: 50vw; height: 50vw; border-radius: 50%; filter: blur(150px); z-index: -1; opacity: 0.15; pointer-events: none; }
        .glow-1 { top: -10%; left: -10%; background: #d946ef; }
        .glow-2 { bottom: -10%; right: -10%; background: #06b6d4; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0a0f; }
        ::-webkit-scrollbar-thumb { background: #1f1f27; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

        /* Transisi Hover Kartu Idol */
        .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { border-color: rgba(217, 70, 239, 0.3); box-shadow: 0 10px 40px -10px rgba(217, 70, 239, 0.15); transform: translateY(-4px); }
    </style>
</head>
<body class="antialiased min-h-screen pt-24 pb-20 relative">

    <!-- BACKGROUND GLOW -->
    <div class="bg-glow glow-1"></div>
    <div class="bg-glow glow-2"></div>

    <!-- NAVBAR (Lengkap dengan menu Groups) -->
    <nav class="fixed top-0 w-full z-50 bg-bg/80 backdrop-blur-lg border-b border-border">
        <div class="max-w-7xl mx-auto px-4 md:px-8 h-20 flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="text-2xl font-display font-black tracking-tight flex items-center gap-2">
                K-PLAY<span class="text-accent text-3xl leading-none">.</span>
            </a>
            
            <!-- Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="/" class="text-sm font-semibold text-zinc-400 hover:text-white transition-colors">Home</a>
                <a href="/idols" class="text-sm font-bold text-white relative after:absolute after:bottom-[-5px] after:left-0 after:w-full after:h-[2px] after:bg-accent">Idols</a>
                <a href="/groups" class="text-sm font-semibold text-zinc-400 hover:text-white transition-colors">Groups</a>
                <a href="#" class="text-sm font-semibold text-zinc-400 hover:text-white transition-colors">Community</a>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-6">
                <button class="text-zinc-400 hover:text-white transition-colors">🔍</button>
                <a href="#" class="text-sm font-bold bg-white text-black px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-colors">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 md:px-8 w-full animate-fade-in">
        
        <!-- HEADER -->
        <div class="mb-12 animate-slide-up">
            <h1 class="text-4xl md:text-6xl font-display font-black text-white mb-4">
                MEET THE <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-cyan">IDOLS.</span>
            </h1>
            <p class="text-zinc-400 text-lg max-w-2xl">
                Koleksi visual eksklusif dari idol K-Pop paling populer saat ini. Dari vokalis utama hingga dancer terbaik.
            </p>
        </div>

        <!-- GRID GALERI IDOL -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 animate-slide-up">
            @foreach($idols as $idol)
                <div class="bg-surface border border-border rounded-2xl p-2.5 card-hover cursor-pointer group">
                    
                    <!-- Container Foto -->
                    <div class="aspect-[4/5] rounded-xl overflow-hidden relative mb-3 bg-zinc-800">
                        <img src="{{ $idol['foto'] }}" alt="{{ $idol['nama'] }}" class="w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700 ease-out">
                        
                        <!-- Gradient Hitam -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        
                        <!-- Badge Followers -->
                        <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-md px-2 py-1 rounded-md text-[10px] font-bold text-white border border-white/10">
                            ⭐ {{ $idol['followers'] }}
                        </div>

                        <!-- Info Nama & Grup -->
                        <div class="absolute bottom-3 left-3 right-3 text-center">
                            <p class="text-[10px] text-accent font-bold mb-0.5 tracking-wider uppercase">{{ $idol['grup'] }}</p>
                            <h3 class="text-base font-bold text-white leading-tight">{{ $idol['nama'] }}</h3>
                        </div>
                    </div>
                    
                    <!-- Info Posisi -->
                    <div class="text-center pb-1">
                        <p class="text-xs text-zinc-400">{{ $idol['posisi'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </main>

</body>
</html>