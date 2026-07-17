@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('header', 'Edit Produk')

@section('content')
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-brand-600 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Produk
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-brand-500 to-brand-600 p-6 text-white">
            <h2 class="text-2xl font-bold">Edit Produk</h2>
            <p class="mt-2 opacity-90">Update informasi produk "{{ $product->name }}"</p>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Produk -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                    placeholder="Contoh: Markisa Madu Cianjur Premium">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" required
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga & Stok -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0"
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                        placeholder="50000">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                        placeholder="100">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Produk</label>
                <textarea name="description" id="description" rows="5"
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                    placeholder="Jelaskan produk Anda secara detail...">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Gambar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                
                <!-- Gambar Saat Ini -->
                @if($product->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-xs rounded-lg shadow-sm border-2 border-gray-200">
                    </div>
                @endif
                
                <!-- Upload Area -->
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-brand-500 transition">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-600 hover:text-brand-500 focus-within:outline-none">
                                <span>Upload gambar baru</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">atau biarkan kosong untuk tetap pakai gambar lama</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB</p>
                    </div>
                </div>
                
                <!-- Preview Gambar Baru -->
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview gambar baru:</p>
                    <img id="preview" src="" alt="Preview" class="max-w-xs rounded-lg shadow-sm border-2 border-brand-500">
                </div>
                
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg shadow-md transition">
                    Update Produk
                </button>
            </div>
        </form>
    </div>

    <!-- Script untuk Preview Gambar -->
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

@endsection