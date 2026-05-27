@extends('layouts.admin')

@section('header', 'Categories')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-zinc-400">Manage your product categories</p>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary-admin px-4 py-2 text-sm">
        <i class="fas fa-plus mr-1.5 text-xs"></i> New Category
    </a>
</div>

<div class="card-admin overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left table-admin">
            <thead>
                <tr>
                    <th class="px-6 py-3 w-16">Image</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3 text-center w-32">Products</th>
                    <th class="px-6 py-3 text-right w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-10 h-10 object-cover rounded border border-zinc-800">
                        @else
                            <div class="w-10 h-10 rounded bg-[#111113] flex items-center justify-center border border-zinc-800 text-zinc-600">
                                <i class="fas fa-image text-xs"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-zinc-200">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-zinc-500">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700 text-xs font-medium">
                            {{ $category->products_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-zinc-400 hover:text-blue-400 transition-colors" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="confirmAction(event, 'Delete Category', 'Are you sure you want to delete this category? Products associated with it might be affected.', 'Yes, delete it')" class="text-zinc-400 hover:text-red-500 transition-colors" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
