@extends('layouts.admin')

@section('header', 'Add Category')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('admin.categories.index') }}" class="text-zinc-400 hover:text-white mr-4 transition-colors">
        <i class="fas fa-arrow-left"></i>
    </a>
    <p class="text-sm text-zinc-400">Create a new product category</p>
</div>

<div class="card-admin p-6 sm:p-8 w-full">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-zinc-300 mb-2">Category Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                   class="w-full bg-[#09090b] border border-zinc-800 rounded-md px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors placeholder-zinc-600"
                   placeholder="e.g. Action Games">
            @error('name')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-zinc-300 mb-2">Category Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="w-full bg-[#0a0a0a] border border-zinc-800 rounded-md px-3 py-2 text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#171717] file:text-[#ececf1] hover:file:bg-[#27272a] transition-all">
            @error('image')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end pt-4 border-t border-zinc-800/50">
            <button type="submit" class="btn-primary-admin px-5 py-2 text-sm">
                Save Category
            </button>
        </div>
    </form>
</div>
@endsection
