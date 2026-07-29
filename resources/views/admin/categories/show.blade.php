@extends('layouts.admin')

@section('title', 'Detail Kategori')
@section('header', 'Detail Kategori')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-6">
                ← Kembali
            </a>

            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-600">ID Kategori</label>
                    <p class="text-lg font-semibold text-gray-900">#{{ $category->id }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Nama Kategori</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $category->name }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Deskripsi</label>
                    <p class="text-gray-700">{{ $category->description ?? '-' }}</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Jumlah Produk</label>
                    <p class="text-gray-700">{{ $category->products->count() }} produk</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Dibuat Pada</label>
                    <p class="text-gray-700">{{ $category->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700">
                    Edit Kategori
                </a>
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection