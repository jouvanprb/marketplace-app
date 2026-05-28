@extends('layouts.front')

@section('title', isset($orders) ? 'Transaction History - ' . config('app.name') : 'Cek Transaksi - ' . config('app.name'))

@section('content')
<div class="{{ isset($orders) && !$orders->isEmpty() ? 'max-w-7xl' : 'max-w-xl' }} mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-24 transition-all duration-300">
    
    @if(isset($orders))
        @if($orders->isEmpty())
            <!-- Empty State for Logged In User or Guest with No Orders -->
            <div class="card-glass rounded-3xl p-12 text-center border border-white/5 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-orange-500/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-purple-500/5 rounded-full blur-3xl"></div>

                <div class="w-16 h-16 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-500 flex items-center justify-center text-xl mx-auto mb-4 relative z-10">
                    <i class="fas fa-history"></i>
                </div>
                <h2 class="text-xl font-bold text-white relative z-10">No Transactions Yet</h2>
                <p class="text-zinc-400 text-sm mt-2 mb-8 max-w-sm mx-auto relative z-10">
                    @auth
                        You haven't made any purchases under your account yet. Let's find your first favorite game pack!
                    @else
                        We couldn't find any transaction history for WhatsApp number: <span class="font-mono text-white">{{ $phone }}</span>
                    @endauth
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center relative z-10">
                    <a href="{{ route('home') }}" class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 text-white font-bold px-6 py-3 rounded-xl text-xs transition-all shadow-lg shadow-orange-500/10 cursor-pointer">
                        <i class="fas fa-shopping-bag mr-1.5"></i> Browse Games Store
                    </a>
                    @guest
                        <a href="{{ route('track.index') }}" class="bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border border-zinc-800 px-6 py-3 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                            Try Another Number
                        </a>
                    @endguest
                </div>
            </div>
        @else
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-2 text-orange-500 font-bold text-xs uppercase tracking-wider mb-1">
                        @auth
                            <i class="fas fa-user-circle"></i> Logged-in Account
                        @else
                            <i class="fab fa-whatsapp"></i> Verified WhatsApp Number
                        @endauth
                    </div>
                    <h1 class="text-2xl font-extrabold text-white">Transaction History</h1>
                    <p class="text-zinc-550 text-sm mt-1">
                        @auth
                            Showing all orders placed under your account <span class="font-semibold text-zinc-300">{{ auth()->user()->email }}</span>
                        @else
                            Showing all orders placed with phone number <span class="font-mono text-zinc-300">{{ $phone }}</span>
                        @endauth
                    </p>
                </div>
                
                @guest
                    <a href="{{ route('track.index') }}" class="bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border border-zinc-800 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-search"></i> Search Another Phone Number
                    </a>
                @endguest
            </div>

            <!-- Orders Table Card -->
            <div class="card-glass rounded-3xl border border-white/5 shadow-2xl overflow-hidden relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-zinc-800/80 bg-zinc-900/30">
                                <th class="px-6 py-4.5 text-[10px] font-extrabold text-zinc-500 uppercase tracking-widest">Date & Time</th>
                                <th class="px-6 py-4.5 text-[10px] font-extrabold text-zinc-500 uppercase tracking-widest">Invoice Code</th>
                                <th class="px-6 py-4.5 text-[10px] font-extrabold text-zinc-500 uppercase tracking-widest">Product</th>
                                <th class="px-6 py-4.5 text-[10px] font-extrabold text-zinc-500 uppercase tracking-widest">Type</th>
                                <th class="px-6 py-4.5 text-[10px] font-extrabold text-zinc-500 uppercase tracking-widest">Amount</th>
                                <th class="px-6 py-4.5 text-[10px] font-extrabold text-zinc-500 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4.5 text-[10px] font-extrabold text-zinc-500 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/40">
                            @foreach($orders as $order)
                                <tr class="hover:bg-white/[0.015] transition-all">
                                    <td class="px-6 py-4 text-xs font-semibold text-zinc-300 whitespace-nowrap">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-zinc-400 font-mono whitespace-nowrap">
                                        {{ $order->order_code }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-white whitespace-nowrap">
                                        {{ $order->items->first()->product->name ?? 'Premium Package' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-[9px] text-zinc-400 bg-zinc-800/60 border border-zinc-800/40 px-2 py-0.5 rounded font-mono font-bold uppercase">
                                            {{ $order->items->first()->product->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-extrabold text-white font-mono whitespace-nowrap">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->status === 'paid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/25">
                                                PAID
                                            </span>
                                        @elseif($order->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-yellow-500/10 text-yellow-450 border border-yellow-500/25">
                                                PENDING
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-500/10 text-red-400 border border-red-500/25">
                                                FAILED
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('order.receipt', ['order_code' => $order->order_code]) }}" 
                                           class="inline-flex items-center gap-1.5 bg-white hover:bg-zinc-200 text-zinc-950 font-bold px-3 py-1.5 rounded-lg text-[10px] transition-all cursor-pointer shadow-md">
                                            <i class="fas fa-file-invoice text-[9px]"></i> View Receipt
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    @else
        <!-- ========================================== -->
        <!-- VIEW 2: PHONE NUMBER SEARCH FORM           -->
        <!-- ========================================== -->
        
        <!-- Track Title Header -->
        <div class="text-center mb-10">
            <div class="w-16 h-16 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-500 flex items-center justify-center text-2xl mx-auto mb-4 shadow-lg shadow-orange-500/15 animate-pulse">
                <i class="fas fa-search-dollar"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-display">Cek Transaksi</h1>
            <p class="text-zinc-400 text-sm mt-2 max-w-sm mx-auto">Masukkan nomor WhatsApp Anda untuk melihat seluruh riwayat dan status pesanan game Anda.</p>
        </div>

        <!-- Error/Failure Status Block -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-semibold rounded-2xl flex items-center">
                <span class="w-1.5 h-1.5 rounded-full bg-red-450 mr-2.5 animate-pulse"></span>
                {{ session('error') }}
            </div>
        @endif

        <!-- Search Form Card -->
        <div class="card-glass rounded-3xl p-6 sm:p-8 border border-white/5 shadow-2xl relative overflow-hidden">
            <!-- Ambient Decoration -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-orange-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-purple-500/5 rounded-full blur-3xl"></div>

            <form method="POST" action="{{ route('track.search') }}" class="space-y-6 relative z-10">
                @csrf

                <!-- Input: WhatsApp Number -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-zinc-400 mb-2.5 uppercase tracking-wider">Nomor WhatsApp Pembeli</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-zinc-500">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </span>
                        <input type="text" name="phone" id="phone" required 
                               placeholder="e.g. 08123456789" 
                               value="{{ old('phone') }}"
                               class="w-full bg-[#09090b] border border-zinc-800 rounded-xl pl-11 pr-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700 font-mono">
                    </div>
                    @error('phone')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <!-- Track Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-500/20 btn-glow transition-all active:scale-95 text-sm cursor-pointer">
                        <i class="fas fa-search mr-2"></i> Cek Riwayat Transaksi
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Footer Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-zinc-500 hover:text-white transition-colors text-xs font-semibold">
                <i class="fas fa-arrow-left mr-1.5"></i> Back to Catalog Store
            </a>
        </div>
    @endif

</div>
@endsection
