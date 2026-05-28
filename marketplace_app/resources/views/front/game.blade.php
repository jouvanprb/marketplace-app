@extends('layouts.front')

@section('title', 'Top Up ' . $category->name . ' - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ 
    activeTab: 'topup', 
    selectedProductId: '',
    selectedProductPrice: 0,
    selectedProductName: '',
    selectProduct(id, price, name) {
        this.selectedProductId = id;
        this.selectedProductPrice = price;
        this.selectedProductName = name;
    }
}">
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-zinc-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-[10px] mx-2"></i>
                    <span class="text-zinc-300">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Game Banner/Header -->
    <div class="relative rounded-3xl overflow-hidden mb-12 border border-zinc-800 shadow-2xl bg-black min-h-[220px] flex items-end p-8 md:p-12">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-purple-500/10 blur-3xl opacity-50"></div>
        @if($category->image)
            <img src="{{ Storage::url($category->image) }}" class="absolute inset-0 w-full h-full object-cover opacity-20 blur-sm scale-105">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-[#09090b] via-[#09090b]/50 to-transparent"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            @if($category->image)
                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-24 h-24 rounded-2xl object-cover shadow-2xl border border-zinc-800 ring-4 ring-orange-500/20">
            @else
                <div class="w-24 h-24 rounded-2xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-700">
                    <i class="fas fa-gamepad text-4xl"></i>
                </div>
            @endif
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">{{ $category->name }}</h1>
                <p class="text-zinc-400 text-sm mt-2">Instant Top-ups & Account Purchases. Secure Payments via Midtrans.</p>
            </div>
        </div>
    </div>

    <!-- Integrated Purchase Form -->
    <form :action="'/checkout/' + selectedProductId" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <!-- Left 2 Columns: Selection & Inputs -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Step 1: Game Account (Shows always for Topup, optional for Account) -->
            <div class="card-glass rounded-3xl p-6 sm:p-8 border border-white/5 shadow-xl" x-show="activeTab === 'topup'">
                <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold">1</span>
                    Enter Game Account Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="game_id" class="block text-xs font-medium text-zinc-400 mb-2">Player ID / User ID</label>
                        <input type="text" name="game_id" id="game_id" :required="activeTab === 'topup'" placeholder="e.g. 123456789" value="{{ old('game_id') }}"
                               class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                        @error('game_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="zone_id" class="block text-xs font-medium text-zinc-400 mb-2">Zone ID / Server ID (Optional)</label>
                        <input type="text" name="zone_id" id="zone_id" placeholder="e.g. 1234" value="{{ old('zone_id') }}"
                               class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                        @error('zone_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-[10px] text-zinc-600 mt-1.5"><i class="fas fa-info-circle mr-1"></i> Required for games like Mobile Legends.</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Select Package -->
            <div class="card-glass rounded-3xl p-6 sm:p-8 border border-white/5 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-lg font-bold text-white flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold" x-text="activeTab === 'topup' ? '2' : '1'">2</span>
                        Select Item / Package
                    </h2>
                    
                    <!-- Tabs -->
                    <div class="flex bg-zinc-900/60 p-1 rounded-xl border border-zinc-800">
                        <button type="button" @click="activeTab = 'topup'; selectedProductId = ''; selectedProductPrice = 0; selectedProductName = ''" 
                                :class="activeTab === 'topup' ? 'bg-orange-500 text-white shadow-lg' : 'text-zinc-400 hover:text-white'" 
                                class="px-5 py-2 rounded-lg text-xs font-bold transition-all">
                            <i class="fas fa-bolt mr-1.5"></i> Top Up
                        </button>
                        <button type="button" @click="activeTab = 'account'; selectedProductId = ''; selectedProductPrice = 0; selectedProductName = ''" 
                                :class="activeTab === 'account' ? 'bg-orange-500 text-white shadow-lg' : 'text-zinc-400 hover:text-white'" 
                                class="px-5 py-2 rounded-lg text-xs font-bold transition-all">
                            <i class="fas fa-user-circle mr-1.5"></i> Beli Akun
                        </button>
                    </div>
                </div>

                <!-- TOP UP CONTENT -->
                <div x-show="activeTab === 'topup'">
                    @if($topups->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($topups as $product)
                            <div @click="selectProduct('{{ $product->id }}', {{ $product->price }}, '{{ $product->name }}')" 
                                 :class="selectedProductId == '{{ $product->id }}' ? 'border-orange-500 ring-2 ring-orange-500/20 bg-orange-500/5' : 'border-zinc-800 bg-[#09090b]/40 hover:border-zinc-700'"
                                 class="border rounded-2xl p-5 cursor-pointer transition-all flex flex-col justify-between relative group overflow-hidden">
                                
                                <!-- Checkmark -->
                                <div x-show="selectedProductId == '{{ $product->id }}'" class="absolute top-2 right-2 w-5 h-5 rounded-full bg-orange-500 flex items-center justify-center text-white text-[10px]">
                                    <i class="fas fa-check"></i>
                                </div>

                                <div class="mb-4">
                                    <p class="text-xs text-zinc-500 font-medium mb-1">Package</p>
                                    <h4 class="text-white font-bold text-sm leading-snug group-hover:text-orange-400 transition-colors">{{ $product->name }}</h4>
                                </div>
                                <div class="mt-auto">
                                    <p class="text-xs text-zinc-600 mb-0.5">Price</p>
                                    <p class="text-sm font-extrabold text-orange-400">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 border border-dashed border-zinc-800 rounded-2xl">
                            <i class="fas fa-box-open text-2xl text-zinc-600 mb-2"></i>
                            <p class="text-zinc-500 text-sm">No top-up items currently available for this game.</p>
                        </div>
                    @endif
                </div>

                <!-- BUY ACCOUNT CONTENT -->
                <div x-show="activeTab === 'account'" style="display: none;">
                    @if($accounts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($accounts as $product)
                            <div @click="selectProduct('{{ $product->id }}', {{ $product->price }}, '{{ $product->name }}')" 
                                 :class="selectedProductId == '{{ $product->id }}' ? 'border-orange-500 ring-2 ring-orange-500/20 bg-orange-500/5' : 'border-zinc-800 bg-[#09090b]/40 hover:border-zinc-700'"
                                 class="border rounded-2xl overflow-hidden cursor-pointer transition-all flex flex-col justify-between relative group">
                                
                                <!-- Checkmark -->
                                <div x-show="selectedProductId == '{{ $product->id }}'" class="absolute top-3 right-3 z-10 w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-white text-xs">
                                    <i class="fas fa-check"></i>
                                </div>

                                <!-- Cover Image -->
                                <div class="relative w-full pb-[50%] bg-[#111113] overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <i class="fas fa-image text-zinc-800 text-xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-5 flex-grow flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-white font-bold text-sm leading-snug group-hover:text-orange-400 transition-colors mb-2 line-clamp-1">{{ $product->name }}</h4>
                                        <p class="text-xs text-zinc-400 line-clamp-2 mb-4">{{ $product->description ?? 'No description.' }}</p>
                                    </div>
                                    <div class="flex items-end justify-between mt-auto">
                                        <div>
                                            <p class="text-[10px] text-zinc-500 mb-0.5">Stock</p>
                                            <p class="text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                                {{ $product->stock > 0 ? $product->stock . ' Left' : 'Out of Stock' }}
                                            </p>
                                        </div>
                                        <p class="text-sm font-extrabold text-orange-400">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 border border-dashed border-zinc-800 rounded-2xl">
                            <i class="fas fa-box-open text-2xl text-zinc-600 mb-2"></i>
                            <p class="text-zinc-500 text-sm">No premium accounts currently available for this game.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Right 1 Column: Contact Details & Checkout Summary -->
        <div class="space-y-8">
            <!-- Step 3: Contact & Delivery Info -->
            <div class="card-glass rounded-3xl p-6 sm:p-8 border border-white/5 shadow-xl">
                <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold" x-text="activeTab === 'topup' ? '3' : '2'">3</span>
                    Contact Information
                </h2>
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-xs font-medium text-zinc-400 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" required placeholder="yourname@gmail.com" value="{{ old('email', auth()->user()->email ?? '') }}"
                               class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-medium text-zinc-400 mb-2">WhatsApp / Phone Number</label>
                        <input type="text" name="phone" id="phone" required placeholder="e.g. 08123456789" value="{{ old('phone') }}"
                               class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-[10px] text-zinc-600 mt-1.5"><i class="fas fa-info-circle mr-1"></i> Order notifications will be sent here.</p>
                    </div>
                </div>
            </div>

            <!-- Step 4: Checkout Summary -->
            <div class="card-glass rounded-3xl p-6 sm:p-8 border border-white/5 shadow-xl relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-24 h-24 bg-orange-500/10 rounded-full blur-xl"></div>
                
                <h2 class="text-lg font-bold text-white mb-6">Payment Summary</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-xs text-zinc-400">
                        <span>Selected Package:</span>
                        <span class="text-white font-semibold truncate max-w-[150px]" x-text="selectedProductName || 'None'"></span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-zinc-400">
                        <span>Service Type:</span>
                        <span class="text-white font-semibold uppercase tracking-wider" x-text="activeTab"></span>
                    </div>
                    <div class="border-t border-zinc-800/80 pt-4 flex justify-between items-center">
                        <span class="text-xs text-zinc-400 font-bold uppercase tracking-wider">Total:</span>
                        <span class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500" 
                              x-text="selectedProductPrice ? 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedProductPrice) : 'Rp 0'"></span>
                    </div>
                </div>

                <!-- Submit Trigger -->
                <button type="submit" 
                        :disabled="!selectedProductId"
                        :class="selectedProductId ? 'bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 text-white shadow-lg shadow-orange-500/25 btn-glow cursor-pointer' : 'bg-zinc-800 text-zinc-500 cursor-not-allowed'"
                        class="w-full font-bold py-4 rounded-xl transition-all text-center text-base">
                    <span x-text="selectedProductId ? 'Pay Now with Midtrans' : 'Select a package to pay'"></span>
                </button>
                <p class="text-center text-[10px] text-zinc-500 mt-4"><i class="fas fa-lock mr-1"></i> Secure payment processed by Midtrans.</p>
            </div>
        </div>
    </form>
</div>
@endsection
