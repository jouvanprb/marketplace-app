@extends('layouts.admin')

@section('header', 'Orders')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-zinc-400">Track and manage customer orders</p>
</div>

<div class="card-admin overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left table-admin">
            <thead>
                <tr>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Items</th>
                    <th class="px-6 py-3">Total Amount</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 font-medium text-zinc-300">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 text-zinc-300">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-zinc-500">{{ $order->items->count() }} items</td>
                    <td class="px-6 py-4 text-[#ececf1] font-medium">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium 
                            @if($order->status == 'paid') bg-emerald-500/10 text-emerald-500 border border-emerald-500/20
                            @elseif($order->status == 'pending') bg-amber-500/10 text-amber-500 border border-amber-500/20
                            @elseif($order->status == 'failed' || $order->status == 'expired') bg-red-500/10 text-red-500 border border-red-500/20
                            @else bg-blue-500/10 text-blue-500 border border-blue-500/20 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-zinc-500">{{ $order->created_at->format('M d, Y, H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-zinc-400 hover:text-white transition-colors" title="View Detail">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-zinc-500">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
