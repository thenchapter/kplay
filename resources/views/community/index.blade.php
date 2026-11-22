@extends('layouts.app')
@section('title', 'Komunitas — K-PLAY')

@section('content')
<div class="px-4 py-8 animate-fade-up">
    <div class="mb-10 text-center max-w-2xl mx-auto">
        <h1 class="text-4xl font-display font-bold gradient-text mb-3">Komunitas Fandom</h1>
        <p class="text-slate-400">Temukan dan ikuti komunitas penggemar grup favoritmu. Berbagi karya, berdiskusi, dan berteman dengan sesama fans!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fandoms as $f)
            @php $isFollowed = in_array($f['slug'], $followed); @endphp
            <div class="card-neon glass rounded-2xl p-6 relative overflow-hidden group">
                <!-- Background Glow -->
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-gradient-to-br {{ $f['warna'] }} opacity-20 blur-2xl rounded-full"></div>
                
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-4">
                        @if(!empty($f['logo']))
                            <img src="{{ $f['logo'] }}" alt="{{ $f['nama'] }}" 
                                 class="w-16 h-16 rounded-2xl object-cover shadow-lg bg-slate-800" 
                                 referrerpolicy="no-referrer" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            
                            <div style="display: none;" class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $f['warna'] }} flex items-center justify-center font-display font-bold text-2xl shadow-lg">
                                {{ substr($f['nama'], 0, 1) }}
                            </div>
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $f['warna'] }} flex items-center justify-center font-display font-bold text-2xl shadow-lg">
                                {{ substr($f['nama'], 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="text-2xl font-display font-bold mb-1">{{ $f['nama'] }}</h3>
                    <p class="text-sm text-slate-400 mb-4 flex-1">{{ $f['deskripsi'] }}</p>
                    
                    <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg> 
                            {{ number_format($f['member_count']) }} Members
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('community.show', $f['slug']) }}" class="flex-1 text-center py-2.5 rounded-xl border border-slate-600 hover:bg-slate-800 transition font-semibold text-sm">
                            Masuk Feed
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 glass rounded-2xl border border-dashed border-slate-700">
                <div class="text-5xl mb-4">💜</div>
                <h3 class="text-xl font-bold text-white mb-2">Belum Ada Komunitas</h3>
                <p class="text-slate-400">Komunitas fandom akan segera hadir. Silakan cek kembali nanti!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection