@extends('layouts.front')

@section('title', 'Complete Payment - ' . config('app.name'))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-glass rounded-3xl p-8 md:p-12 text-center border border-white/10 shadow-2xl relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-orange-500/20 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-gradient-to-tr from-purple-500/20 to-transparent rounded-full blur-3xl"></div>

        <div class="w-16 h-16 bg-orange-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-orange-500/20">
            <i class="fas fa-wallet text-orange-500 text-2xl animate-pulse"></i>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Complete Your Payment</h1>
        <p class="text-zinc-400 text-sm max-w-md mx-auto mb-8">
            Please complete your transaction using the secure Midtrans payment window.
        </p>

        <!-- Invoice Box -->
        <div class="bg-[#09090b] rounded-2xl p-6 border border-zinc-800 text-left mb-8 space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-zinc-800/80">
                <span class="text-xs text-zinc-500 uppercase tracking-wider">Invoice Code</span>
                <span class="text-sm font-semibold text-white font-mono">{{ $order->order_code }}</span>
            </div>
            
            <div class="flex justify-between items-center pb-3 border-b border-zinc-800/80">
                <span class="text-xs text-zinc-500 uppercase tracking-wider">Product Name</span>
                <span class="text-sm font-semibold text-white truncate max-w-[200px]">{{ $order->items->first()->product->name ?? 'Game Product' }}</span>
            </div>

            @if(isset($order->payment_details['game_details']))
                <div class="flex justify-between items-center pb-3 border-b border-zinc-800/80">
                    <span class="text-xs text-zinc-500 uppercase tracking-wider">Game Account ID</span>
                    <span class="text-sm font-semibold text-orange-400 font-mono">
                        {{ $order->payment_details['game_details']['game_id'] }}
                        @if($order->payment_details['game_details']['zone_id'])
                            ({{ $order->payment_details['game_details']['zone_id'] }})
                        @endif
                    </span>
                </div>
            @endif

            <div class="flex justify-between items-center">
                <span class="text-xs text-zinc-500 uppercase tracking-wider">Total Amount</span>
                <span class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Buttons -->
        <div class="space-y-4">
            <button id="pay-button" class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 text-white font-bold py-4 rounded-xl transition-all btn-glow text-center text-lg shadow-lg shadow-orange-500/25">
                Pay Now
            </button>
            <a href="{{ route('home') }}" class="block w-full bg-zinc-900 hover:bg-zinc-800 text-zinc-300 font-semibold py-3.5 rounded-xl transition-all border border-zinc-800 text-sm">
                Cancel & Return Home
            </a>
        </div>
    </div>
</div>

<!-- Load Midtrans Snap JS -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const payButton = document.getElementById('pay-button');
        
        function triggerSnap() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id + "&payment_type=" + result.payment_type;
                },
                onPending: function(result) {
                    window.location.href = "{{ route('payment.unfinish') }}?order_id=" + result.order_id;
                },
                onError: function(result) {
                    window.location.href = "{{ route('payment.error') }}?order_id=" + result.order_id;
                },
                onClose: function() {
                    console.log('customer closed the popup without finishing the payment');
                }
            });
        }

        // Auto trigger on load
        triggerSnap();

        // Trigger on click
        payButton.addEventListener('click', function(e) {
            e.preventDefault();
            triggerSnap();
        });
    });
</script>
@endsection
