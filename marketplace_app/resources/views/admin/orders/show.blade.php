@extends('layouts.admin')

@section('header', 'Order Details')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('admin.orders.index') }}" class="text-zinc-400 hover:text-white mr-4 transition-colors">
        <i class="fas fa-arrow-left"></i>
    </a>
    <p class="text-sm text-zinc-400">Order ID: <span class="text-white font-mono">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="card-admin overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-800/50">
                <h3 class="text-sm font-semibold text-zinc-200">Items Purchased</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left table-admin">
                    <thead>
                        <tr>
                            <th class="px-6 py-3">Product</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3 text-right">Price</th>
                            <th class="px-6 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}" class="w-8 h-8 rounded object-cover mr-3 border border-zinc-800">
                                    @else
                                        <div class="w-8 h-8 rounded bg-[#111113] flex items-center justify-center mr-3 border border-zinc-800 text-zinc-600">
                                            <i class="fas fa-image text-xs"></i>
                                        </div>
                                    @endif
                                    <p class="text-zinc-200 font-medium">{{ $item->product->name ?? 'Unknown Product' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-zinc-400">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right text-zinc-400">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-zinc-200 font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-[#0a0a0a]">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-[#8f8f9d] font-medium text-sm border-t border-[#1f1f22]">Total Amount</td>
                            <td class="px-6 py-4 text-right text-[#ea580c] font-semibold text-lg border-t border-[#1f1f22]">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="card-admin p-6">
            <h3 class="text-sm font-semibold text-zinc-200 mb-4">Customer Information</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-zinc-500 mb-1">Name</p>
                    <p class="text-sm text-zinc-200 font-medium">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-500 mb-1">Email</p>
                    <p class="text-sm text-zinc-200 font-medium">{{ $order->user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="card-admin p-6">
            <h3 class="text-sm font-semibold text-zinc-200 mb-4">Order Status</h3>
            <div class="mb-5">
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium 
                    @if($order->status == 'paid') bg-emerald-500/10 text-emerald-500 border border-emerald-500/20
                    @elseif($order->status == 'pending') bg-amber-500/10 text-amber-500 border border-amber-500/20
                    @elseif($order->status == 'failed' || $order->status == 'expired') bg-red-500/10 text-red-500 border border-red-500/20
                    @else bg-blue-500/10 text-blue-500 border border-blue-500/20 @endif">
                    {{ ucfirst($order->status) }}
                </span>
                <p class="text-xs text-zinc-500 mt-2">Last updated: {{ $order->updated_at->diffForHumans() }}</p>
            </div>
            
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="status" class="block text-xs font-medium text-zinc-400 mb-2">Change Status</label>
                    <div class="relative">
                        <select name="status" id="status" class="w-full bg-[#0a0a0a] border border-[#1f1f22] rounded-md px-3.5 py-2 text-sm text-[#ececf1] focus:outline-none focus:border-[#52525b] appearance-none transition-colors cursor-pointer">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->status == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="expired" {{ $order->status == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full btn-primary-admin py-2 text-sm">
                    Apply Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
