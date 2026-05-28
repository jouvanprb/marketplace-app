@extends('layouts.front')

@section('title', 'Transaction Receipt - ' . config('app.name'))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-24 print:pt-0 print:pb-0">
    
    <!-- Success Status Indicator -->
    <div class="text-center mb-8 print:hidden">
        @if($order->status === 'paid')
            <div class="w-16 h-16 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-emerald-500/20 animate-bounce">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white">Transaction Successful!</h1>
            <p class="text-zinc-400 text-sm mt-1">Thank you for your purchase. Your order has been processed.</p>
        @elseif($order->status === 'pending')
            <div class="w-16 h-16 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-yellow-500/20 animate-pulse">
                <i class="fas fa-clock"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white">Transaction Pending</h1>
            <p class="text-zinc-400 text-sm mt-1">Please complete your payment to process the transaction.</p>
        @else
            <div class="w-16 h-16 rounded-full bg-red-500/10 border border-red-500/20 text-red-450 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-red-500/20">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white">Transaction Failed</h1>
            <p class="text-zinc-400 text-sm mt-1">Something went wrong. Please check your payment details and try again.</p>
        @endif
    </div>

    <!-- Digital Receipt Card -->
    <div class="card-glass rounded-3xl p-6 sm:p-10 border border-white/10 shadow-2xl relative overflow-hidden print:border-none print:shadow-none print:bg-white print:text-black">
        <!-- Ambient Glow Decorations (Hidden in Print) -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-orange-500/10 to-transparent rounded-full blur-3xl print:hidden"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-gradient-to-tr from-purple-500/10 to-transparent rounded-full blur-3xl print:hidden"></div>

        <!-- Receipt Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-zinc-800/80 print:border-zinc-300">
            <div>
                <span class="text-orange-500 font-extrabold text-lg tracking-wider font-mono">RECEIPT</span>
                <h2 class="text-2xl font-bold text-white mt-1 print:text-black">{{ config('app.name') }}</h2>
            </div>
            <div class="mt-4 sm:mt-0 text-left sm:text-right">
                <p class="text-xs text-zinc-500 uppercase tracking-wider">Date Created</p>
                <p class="text-sm font-semibold text-zinc-300 print:text-black mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <!-- Receipt Summary Metadata -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-zinc-800/80 print:border-zinc-300">
            <div>
                <p class="text-[10px] text-zinc-500 uppercase tracking-wider mb-1.5">Invoice Reference</p>
                <div class="flex items-center text-sm font-semibold font-mono text-white print:text-black">
                    <span id="invoice-code">{{ $order->order_code }}</span>
                    <button onclick="copyInvoice()" class="hover:text-orange-400 ml-2.5 transition-colors print:hidden" title="Copy Invoice Code">
                        <i class="far fa-copy text-zinc-500 hover:text-white transition-all"></i>
                    </button>
                </div>
            </div>
            <div>
                <p class="text-[10px] text-zinc-500 uppercase tracking-wider mb-1.5">Payment Method</p>
                <p class="text-sm font-bold text-zinc-300 print:text-black capitalize">{{ $order->payment_method ?? 'Midtrans E-Wallet / Bank' }}</p>
            </div>
        </div>

        <!-- Checkout Item Detail -->
        <div class="py-6 border-b border-zinc-800/80 print:border-zinc-300">
            <h4 class="text-xs font-bold text-zinc-400 print:text-zinc-650 uppercase tracking-wider mb-4">Item Details</h4>
            
            <div class="bg-[#09090b]/40 border border-zinc-800/60 rounded-2xl p-5 space-y-4 print:bg-zinc-100 print:border-zinc-300 print:text-black">
                <div class="flex justify-between items-center text-sm">
                    <div>
                        <p class="font-bold text-white print:text-black">{{ $order->items->first()->product->name ?? 'Premium Package' }}</p>
                        <p class="text-xs text-zinc-500 mt-0.5">Game Category Account / Topup</p>
                    </div>
                    <span class="font-semibold text-zinc-300 print:text-black font-mono">1x</span>
                </div>
                
                @if(isset($order->payment_details['game_details']))
                    <div class="pt-4 border-t border-zinc-800/50 print:border-zinc-300 flex justify-between items-center text-xs">
                        <span class="text-zinc-500 font-medium">Game Account details:</span>
                        <span class="font-extrabold text-orange-400 font-mono">
                            {{ $order->payment_details['game_details']['game_id'] }}
                            @if($order->payment_details['game_details']['zone_id'])
                                ({{ $order->payment_details['game_details']['zone_id'] }})
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Contacts Info -->
        <div class="py-6 border-b border-zinc-800/80 print:border-zinc-300">
            <h4 class="text-xs font-bold text-zinc-400 print:text-zinc-650 uppercase tracking-wider mb-4">Delivery & Contact Info</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-zinc-500">Email Address:</span>
                    <span class="block font-semibold text-zinc-300 print:text-black mt-1 font-mono">
                        {{ $order->payment_details['customer_details']['email'] ?? ($order->user->email ?? 'N/A') }}
                    </span>
                </div>
                <div>
                    <span class="text-zinc-500">WhatsApp / Phone:</span>
                    <span class="block font-semibold text-zinc-300 print:text-black mt-1 font-mono">
                        {{ $order->payment_details['customer_details']['phone'] ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Billing -->
        <div class="pt-6 flex justify-between items-center">
            <div>
                <span class="text-xs text-zinc-500 uppercase tracking-wider">Payment Status</span>
                <div class="mt-1 flex items-center">
                    @if($order->status === 'paid')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> PAID
                        </span>
                    @elseif($order->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-450 border border-yellow-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-450 mr-1.5 animate-pulse"></span> PENDING
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                             FAILED
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs text-zinc-500 uppercase tracking-wider block">Total Amount Paid</span>
                <span class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500 print:text-black print:bg-none">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Action Triggers (Hidden in Print) -->
    <div class="mt-8 flex flex-col sm:flex-row gap-4 print:hidden">
        <a href="{{ route('home') }}" class="flex-1 bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-4 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all text-center text-sm cursor-pointer">
            <i class="fas fa-shopping-bag mr-2"></i> Back to Home Store
        </a>
        <button onclick="window.print()" class="flex-1 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold py-4 rounded-xl transition-all border border-zinc-800 text-sm cursor-pointer shadow-md">
            <i class="fas fa-print mr-2"></i> Print Invoice / PDF
        </button>
    </div>
</div>

<!-- Simple Copy Alert Notification Toast -->
<div id="copy-toast" class="fixed bottom-8 right-8 z-50 bg-emerald-500 text-white text-xs font-bold px-4 py-3 rounded-xl shadow-xl flex items-center gap-2 transform translate-y-24 opacity-0 transition-all duration-300">
    <i class="fas fa-check-circle"></i>
    <span>Invoice code successfully copied to clipboard!</span>
</div>

<script>
    function copyInvoice() {
        const text = document.getElementById('invoice-code').innerText;
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('translate-y-24', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 3000);
        });
    }
</script>

<style>
    @media print {
        /* Hide navbar, footer, and buttons during printing */
        nav, footer, .print\:hidden, #copy-toast {
            display: none !important;
        }
        body, main {
            background: white !important;
            color: black !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .card-glass {
            background: white !important;
            border: none !important;
            color: black !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        /* Make sure gradient text is black on print for premium crisp ink */
        .text-transparent {
            color: black !important;
            background: none !important;
            -webkit-background-clip: text !important;
        }
    }
</style>
@endsection
