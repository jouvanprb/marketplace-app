@extends('layouts.admin')

@section('header', isset($currentType) && $currentType == 'account' ? 'Accounts' : 'Top Up')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-zinc-400">Manage your {{ isset($currentType) && $currentType == 'account' ? 'account' : 'top up' }} products</p>
    <a href="{{ route('admin.products.create', ['type' => $currentType ?? 'topup']) }}" class="btn-primary-admin px-4 py-2 text-sm">
        <i class="fas fa-plus mr-1.5 text-xs"></i> New {{ isset($currentType) && $currentType == 'account' ? 'Account' : 'Top Up' }}
    </a>
</div>

<div class="card-admin overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left table-admin">
            <thead>
                <tr>
                    <th class="px-6 py-3 w-16">Item</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Price</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded border border-zinc-800">
                        @else
                            <div class="w-10 h-10 rounded bg-[#111113] flex items-center justify-center border border-zinc-800 text-zinc-600">
                                <i class="fas fa-image text-xs"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-[#ececf1]">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-[#8f8f9d]">
                        @if($product->type == 'topup')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">Top Up</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-500/10 text-purple-500 border border-purple-500/20">Beli Akun</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-[#8f8f9d]">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-zinc-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-500/10 text-zinc-500 border border-zinc-500/20">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-zinc-400 hover:text-blue-400 transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="confirmAction(event, 'Delete Product', 'Are you sure you want to delete this product? This action cannot be undone.', 'Yes, delete it')" class="text-zinc-400 hover:text-red-500 transition-colors" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-zinc-500">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
