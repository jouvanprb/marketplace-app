@extends('layouts.front')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
    <!-- Promo Banner Carousel -->
    <div class="relative mb-16 overflow-hidden rounded-3xl border border-white/5 shadow-2xl shadow-black/50" 
         x-data="{ 
            activeSlide: 0,
            slidesCount: 3,
            next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount },
            init() { setInterval(() => this.next(), 6000) }
         }"
         x-init="init()">
         
        <!-- Slides -->
        <div class="relative h-[220px] sm:h-[260px] md:h-[300px] w-full bg-[#0b0b0d] flex items-center">
            
            <!-- Slide 1 -->
            <div x-show="activeSlide === 0" x-transition.opacity.duration.600ms
                 class="absolute inset-0 bg-gradient-to-r from-purple-950/70 via-indigo-950/40 to-transparent flex items-center px-8 md:px-16">
                 <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/10 via-transparent to-transparent blur-3xl"></div>
                 <div class="max-w-lg z-10 space-y-3 md:space-y-4">
                     <span class="inline-block bg-purple-500/20 text-purple-300 border border-purple-500/30 text-[9px] font-extrabold uppercase px-2.5 py-0.5 rounded-full tracking-wider">Special Promotion</span>
                     <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight">Dapatkan Bonus <span class="text-purple-400">Diamonds 50%</span>!</h2>
                     <p class="text-zinc-400 text-[11px] sm:text-xs md:text-sm">Top up Mobile Legends & Free Fire sekarang juga dan dapatkan bonus saldo instan langsung masuk ke ID game Anda.</p>
                 </div>
            </div>

            <!-- Slide 2 -->
            <div x-show="activeSlide === 1" x-transition.opacity.duration.600ms
                 class="absolute inset-0 bg-gradient-to-r from-red-950/70 via-orange-950/40 to-transparent flex items-center px-8 md:px-16" style="display: none;">
                 <div class="absolute inset-0 bg-gradient-to-tr from-orange-500/10 via-transparent to-transparent blur-3xl"></div>
                 <div class="max-w-lg z-10 space-y-3 md:space-y-4">
                     <span class="inline-block bg-orange-500/20 text-orange-300 border border-orange-500/30 text-[9px] font-extrabold uppercase px-2.5 py-0.5 rounded-full tracking-wider">Weekly Offer</span>
                     <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight">Diskon Hingga <span class="text-orange-400">20%</span> Semua Game</h2>
                     <p class="text-zinc-400 text-[11px] sm:text-xs md:text-sm">Belanja UC PUBG Mobile, VP Valorant, & Cash PB dengan harga termurah. Pembayaran aman & instan via Midtrans.</p>
                 </div>
            </div>

            <!-- Slide 3 -->
            <div x-show="activeSlide === 2" x-transition.opacity.duration.600ms
                 class="absolute inset-0 bg-gradient-to-r from-cyan-950/70 via-blue-950/40 to-transparent flex items-center px-8 md:px-16" style="display: none;">
                 <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/10 via-transparent to-transparent blur-3xl"></div>
                 <div class="max-w-lg z-10 space-y-3 md:space-y-4">
                     <span class="inline-block bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-[9px] font-extrabold uppercase px-2.5 py-0.5 rounded-full tracking-wider">Account Sale</span>
                     <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight">Beli Akun Game <span class="text-cyan-400">Premium Instan</span></h2>
                     <p class="text-zinc-400 text-[11px] sm:text-xs md:text-sm">Temukan ribuan pilihan akun game level tinggi dengan jaminan aman 100%, dan pengiriman detail kredensial instan.</p>
                 </div>
            </div>
            
        </div>

        <!-- Left/Right Buttons -->
        <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/40 hover:bg-black/60 border border-white/5 flex items-center justify-center text-white/70 hover:text-white transition-all z-20 backdrop-blur">
            <i class="fas fa-chevron-left text-[10px] md:text-xs"></i>
        </button>
        <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 md:w-10 md:h-10 rounded-full bg-black/40 hover:bg-black/60 border border-white/5 flex items-center justify-center text-white/70 hover:text-white transition-all z-20 backdrop-blur">
            <i class="fas fa-chevron-right text-[10px] md:text-xs"></i>
        </button>

        <!-- Dots -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
            <template x-for="i in slidesCount" :key="i">
                <button @click="activeSlide = i - 1" 
                        :class="activeSlide === i - 1 ? 'bg-orange-500 w-6' : 'bg-white/30 hover:bg-white/50 w-2'"
                        class="h-1.5 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </div>

    <!-- Header Section (Select Game + Mobile Search) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5">
                <i class="fas fa-gamepad text-orange-500"></i> Select Game
            </h2>
            <p class="text-xs text-zinc-550 mt-1">Select a game below to browse packages and accounts.</p>
        </div>
        
        <!-- Mobile Search (displays on all screens below lg) -->
        <form action="{{ route('home') }}" method="GET" class="relative w-full sm:max-w-xs lg:hidden">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search game..." 
                   class="w-full bg-[#111113] border border-zinc-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-orange-500 transition-all placeholder-zinc-500 shadow-md">
        </form>
    </div>

    <!-- Games Grid (Completely Borderless & Minimalist) -->
    @if($categories->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('game.show', $category->slug) }}" class="group flex flex-col items-center">
                <!-- Borderless & Modern Cover Image Box -->
                <div class="relative w-full pb-[100%] overflow-hidden rounded-2xl bg-zinc-900 shadow-lg transition-all duration-300 group-hover:scale-[1.03]">
                    @if($category->image)
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-gamepad text-3xl text-zinc-700"></i>
                        </div>
                    @endif
                </div>

                <!-- Game Title (Clean & Modern hover color transition) -->
                <h3 class="text-zinc-300 font-semibold text-xs mt-3 group-hover:text-orange-400 transition-colors text-center tracking-wide">{{ $category->name }}</h3>
            </a>
            @endforeach
        </div>
    @else
        <div class="card-glass rounded-2xl p-12 text-center border-dashed border-2 border-zinc-800 max-w-md mx-auto mt-8">
            <div class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center mx-auto mb-4 text-zinc-500">
                <i class="fas fa-gamepad text-lg"></i>
            </div>
            <h3 class="text-sm font-medium text-white mb-1">No games found</h3>
            <p class="text-zinc-500 text-xs mb-4">We couldn't find any games matching "{{ request('search') }}".</p>
            <a href="{{ route('home') }}" class="inline-block px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-white rounded-lg text-xs font-semibold">Clear Search</a>
        </div>
    @endif
</div>
@endsection
