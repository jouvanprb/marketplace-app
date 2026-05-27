@extends('layouts.admin')

@section('header', 'Edit Product')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('admin.products.index') }}" class="text-zinc-400 hover:text-white mr-4 transition-colors">
        <i class="fas fa-arrow-left"></i>
    </a>
    <p class="text-sm text-zinc-400">Updating product: <span class="text-white">{{ $product->name }}</span></p>
</div>

<div class="card-admin p-6 sm:p-8 w-full">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-300 mb-2">Product Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required 
                       class="w-full bg-[#09090b] border border-zinc-800 rounded-md px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                @error('name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-zinc-300 mb-2">Product Type</label>
                <select name="type" id="type" required 
                        class="w-full bg-[#09090b] border border-zinc-800 rounded-md px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors appearance-none">
                    <option value="topup" {{ old('type', $product->type ?? '') == 'topup' ? 'selected' : '' }}>Top Up</option>
                    <option value="account" {{ old('type', $product->type ?? '') == 'account' ? 'selected' : '' }}>Beli Akun</option>
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-zinc-300 mb-2">Category</label>
                <select name="category_id" id="category_id" required 
                        class="w-full bg-[#09090b] border border-zinc-800 rounded-md px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors appearance-none">
                    <option value="" disabled>Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-zinc-300 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" 
                      class="w-full bg-[#09090b] border border-zinc-800 rounded-md px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">{{ old('description', $product->description) }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="price" class="block text-sm font-medium text-zinc-300 mb-2">Price (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-zinc-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0" step="1"
                           class="w-full bg-[#09090b] border border-zinc-800 rounded-md pl-10 pr-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                </div>
                @error('price')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="stock" class="block text-sm font-medium text-zinc-300 mb-2">Stock (Optional)</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                       class="w-full bg-[#09090b] border border-zinc-800 rounded-md px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                @error('stock')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-zinc-300 mb-2">Current Image</label>
            @if($product->image)
                <div class="mb-4 inline-block relative border border-zinc-800 rounded p-1 bg-[#09090b]">
                    <img src="{{ Storage::url($product->image) }}" alt="Product Image" class="w-24 h-24 object-cover rounded">
                </div>
            @else
                <p class="text-zinc-500 mb-4 text-xs">No image provided.</p>
            @endif
            
            <label for="image" class="block text-sm font-medium text-zinc-300 mb-2">Replace Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="w-full text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-[#111113] file:text-zinc-300 hover:file:bg-[#27272a] hover:file:text-white transition-colors cursor-pointer border border-zinc-800/50 p-1">
            @error('image')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div class="mb-8 pt-2">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded bg-[#09090b] border-zinc-700 text-orange-500 focus:ring-orange-500 focus:ring-offset-[#18181b]">
                <span class="ml-2.5 text-sm text-zinc-300">Active (Visible to customers)</span>
            </label>
            @error('is_active')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end pt-4 border-t border-zinc-800/50">
            <button type="submit" class="btn-primary-admin px-5 py-2 text-sm">
                Update Product
            </button>
        </div>
    </form>
</div>
@endsection
