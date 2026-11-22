<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | K-PLAY</title>
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
        body { background-color: #0a0a0f; color: #f8fafc; font-family: 'Inter', sans-serif; }
        
        /* Efek Latar Belakang Glow */
        .bg-glow { position: fixed; width: 50vw; height: 50vw; border-radius: 50%; filter: blur(150px); z-index: -1; opacity: 0.15; pointer-events: none; }
        .glow-1 { top: -10%; left: -10%; background: #d946ef; }
        .glow-2 { bottom: -10%; right: -10%; background: #06b6d4; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="bg-glow glow-1"></div>
    <div class="bg-glow glow-2"></div>

    <!-- REGISTER CARD -->
    <div class="w-full max-w-md bg-surface border border-border rounded-3xl p-8 md:p-10 relative z-10 animate-slide-up shadow-2xl shadow-accent/5">
        
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-display font-black tracking-tight inline-block mb-2">
                K-PLAY<span class="text-accent text-4xl leading-none">.</span>
            </a>
            <h1 class="text-xl font-display font-bold text-white mb-1">Gabung ke Komunitas!</h1>
            <p class="text-sm text-zinc-400">Buat akun barumu dalam hitungan detik.</p>
        </div>

        <!-- Error Message -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 text-sm p-3 rounded-xl mb-6">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM REGISTER -->
        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Input Nama -->
            <div>
                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="w-full bg-bg border border-border rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all placeholder-zinc-600"
                    placeholder="Rina Amanda">
            </div>

            <!-- Input Username -->
            <div>
                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-zinc-500 font-medium">@</span>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full bg-bg border border-border rounded-xl pl-9 pr-4 py-3 text-sm text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all placeholder-zinc-600"
                        placeholder="rina_kpop">
                </div>
                <p class="text-[10px] text-zinc-500 mt-1">Hanya huruf, angka, dan garis bawah (_).</p>
            </div>

            <!-- Input Email -->
            <div>
                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-bg border border-border rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all placeholder-zinc-600"
                    placeholder="rina@kplay.com">
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="w-full bg-gradient-to-r from-accent to-purple-600 hover:opacity-90 text-white font-bold py-3.5 rounded-xl transition-all shadow-[0_0_20px_rgba(217,70,239,0.3)] mt-4">
                Daftar Sekarang
            </button>
        </form>

        <!-- Link ke Login -->
        <div class="text-center mt-6 pt-6 border-t border-border">
            <p class="text-sm text-zinc-400">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-accent font-semibold hover:text-white transition-colors">Masuk di sini</a>
            </p>
        </div>

    </div>

</body>
</html>