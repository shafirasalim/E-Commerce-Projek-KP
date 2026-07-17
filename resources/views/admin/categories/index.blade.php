@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('header', 'Kelola Kategori')

@section('content')
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header & Add Button -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Kategori</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola semua kategori produk toko Anda</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kategori
        </a>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                #{{ $category->id }}
                            </td>
                            
                            <!-- Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </td>
                            
                            <!-- Description -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate">
                                    {{ $category->description ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Product Count -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-brand-100 text-brand-800">
                                    {{ $category->products->count() }} produk
                                </span>
                            </td>
                            
                            <!-- Created Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $category->created_at->format('d M Y') }}
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-brand-600 hover:text-brand-900">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Produk dalam kategori ini tidak akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

@endsection