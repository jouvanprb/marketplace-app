@extends('layouts.front')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-zinc-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-[10px] mx-2"></i>
                    <a href="{{ route('home', ['category' => $product->category->slug ?? '']) }}" class="hover:text-white transition-colors">{{ $product->category->name ?? 'Uncategorized' }}</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-[10px] mx-2"></i>
                    <span class="text-zinc-300 truncate max-w-[200px]">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-[#111113] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl mb-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Left: Image Gallery -->
            <div class="relative bg-black p-8 lg:p-12 flex items-center justify-center min-h-[400px]">
                <!-- Abstract Glow -->
                <div class="absolute inset-0 bg-gradient-to-tr from-orange-500/10 to-purple-500/10 blur-3xl"></div>
                
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="relative z-10 w-full max-w-md h-auto object-contain rounded-2xl shadow-2xl shadow-black ring-1 ring-white/10">
                @else
                    <div class="relative z-10 w-full max-w-sm aspect-square bg-zinc-900 rounded-2xl border border-zinc-800 flex items-center justify-center text-zinc-700 shadow-2xl">
                        <i class="fas fa-image text-6xl"></i>
                    </div>
                @endif
                
                <div class="absolute top-6 left-6 z-20">
                    @if($product->type == 'topup')
                        <span class="bg-blue-500/90 backdrop-blur text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-bolt"></i> Fast Top Up
                        </span>
                    @else
                        <span class="bg-purple-500/90 backdrop-blur text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-user-circle"></i> Account
                        </span>
                    @endif
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="p-8 lg:p-12 flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-4">
                    @if($product->category && $product->category->image)
                        <img src="{{ Storage::url($product->category->image) }}" class="w-6 h-6 rounded object-cover shadow">
                    @endif
                    <span class="text-orange-400 font-medium text-sm tracking-wide uppercase">{{ $product->category->name ?? 'Uncategorized' }}</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-6 leading-tight">{{ $product->name }}</h1>
                
                <div class="flex items-baseline gap-4 mb-8 pb-8 border-b border-zinc-800">
                    <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                
                <div class="prose prose-invert prose-sm max-w-none mb-8 text-zinc-400 leading-relaxed">
                    @if($product->description)
                        {!! nl2br(e($product->description)) !!}
                    @else
                        <p class="italic text-zinc-600">No description available for this product.</p>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-[#09090b] rounded-xl p-4 border border-zinc-800 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 mb-0.5">Guarantee</p>
                            <p class="text-sm font-medium text-white">100% Secure</p>
                        </div>
                    </div>
                    
                    <div class="bg-[#09090b] rounded-xl p-4 border border-zinc-800 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 mb-0.5">Delivery</p>
                            <p class="text-sm font-medium text-white">Instant / 5 Mins</p>
                        </div>
                    </div>
                    
                    @if($product->type == 'account')
                    <div class="bg-[#09090b] rounded-xl p-4 border border-zinc-800 flex items-center gap-4 col-span-2">
                        <div class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 mb-0.5">Stock Available</p>
                            @if($product->stock > 0)
                                <p class="text-sm font-medium text-emerald-400">{{ $product->stock }} Accounts left</p>
                            @else
                                <p class="text-sm font-medium text-red-400">Out of Stock</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="mt-auto pt-6 border-t border-zinc-800/80">
                    @if($product->type == 'account' && $product->stock <= 0)
                        <button disabled class="w-full bg-zinc-800 text-zinc-500 font-bold py-4 rounded-xl cursor-not-allowed">
                            Out of Stock
                        </button>
                    @else
                        <form action="{{ route('checkout.store', $product->id) }}" method="POST" class="space-y-6">
                            @csrf
                            
                            @if($product->type === 'topup')
                                <!-- Step 1: Game Account Details -->
                                <div class="space-y-3">
                                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center justify-center text-[10px] font-bold">1</span>
                                        Input Game Account
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="game_id" class="block text-xs font-medium text-zinc-400 mb-1.5">Player ID</label>
                                            <input type="text" name="game_id" id="game_id" required placeholder="Enter Player ID" value="{{ old('game_id') }}"
                                                   class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                                            @error('game_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label for="zone_id" class="block text-xs font-medium text-zinc-400 mb-1.5">Zone ID (Optional)</label>
                                            <input type="text" name="zone_id" id="zone_id" placeholder="e.g. 1234" value="{{ old('zone_id') }}"
                                                   class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                                            @error('zone_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Contact Info -->
                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold text-white uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center justify-center text-[10px] font-bold">{{ $product->type === 'topup' ? '2' : '1' }}</span>
                                    Contact & Delivery Info
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="email" class="block text-xs font-medium text-zinc-400 mb-1.5">Email Address</label>
                                        <input type="email" name="email" id="email" required placeholder="yourname@gmail.com" value="{{ old('email', auth()->user()->email ?? '') }}"
                                               class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-xs font-medium text-zinc-400 mb-1.5">WhatsApp / Phone Number</label>
                                        <input type="text" name="phone" id="phone" required placeholder="e.g. 08123456789" value="{{ old('phone') }}"
                                               class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 text-white font-bold py-4 rounded-xl transition-all btn-glow text-center text-lg shadow-lg shadow-orange-500/25">
                                    Pay Now with Midtrans
                                </button>
                                <p class="text-center text-xs text-zinc-500 mt-4"><i class="fas fa-lock mr-1"></i> Secure checkout provided by Midtrans</p>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mb-16">
        <h2 class="text-2xl font-bold text-white mb-8">You might also like</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <a href="{{ route('product.show', $related->id) }}" class="card-glass rounded-2xl overflow-hidden group flex flex-col h-full relative">
                <!-- Badge -->
                <div class="absolute top-3 left-3 z-10">
                    @if($related->type == 'topup')
                        <span class="bg-blue-500/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-lg uppercase tracking-wider">Top Up</span>
                    @else
                        <span class="bg-purple-500/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-lg uppercase tracking-wider">Account</span>
                    @endif
                </div>
                
                <!-- Image -->
                <div class="relative w-full pb-[75%] overflow-hidden bg-[#111113]">
                    @if($related->image)
                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-gamepad text-3xl text-zinc-800"></i>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="text-white font-medium text-sm mb-2 line-clamp-2 leading-tight group-hover:text-orange-400 transition-colors">{{ $related->name }}</h3>
                    <div class="mt-auto pt-2">
                        <p class="text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
