<x-public-layout title="Beranda - Toko Online">
    
    <!-- BANNER AREA (Slider Simulasi) -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="relative w-full h-40 sm:h-64 rounded-xl overflow-hidden shadow-sm group">
            <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Banner" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                <div class="text-center text-white">
                    <h2 class="text-3xl font-bold mb-2">Selamat Datang!</h2>
                    <p class="text-lg">Temukan promo terbaik hari ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KATEGORI -->
    <div class="bg-white py-6 shadow-sm mb-4">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="text-gray-600 font-medium">KATEGORI</h3>
            </div>
            <div class="flex overflow-x-auto space-x-6 pb-2 snap-x snap-mandatory scrollbar-hide" style="-ms-overflow-style: none; scrollbar-width: none;">
                @foreach($categories ?? [] as $category)
                    <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="flex-shrink-0 w-24 flex flex-col items-center snap-start hover:scale-105 transition-transform duration-200 cursor-pointer">
                        <div class="w-16 h-16 rounded-full border border-gray-200 flex items-center justify-center bg-gray-50 mb-2 shadow-sm">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-center text-gray-600 line-clamp-2 leading-tight px-1">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- REKOMENDASI -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div class="sticky top-16 z-40 bg-white pb-2 border-b mb-4">
            <h2 class="text-lg font-bold text-gray-800 py-2">REKOMENDASI</h2>
        </div>

        @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                @foreach($products as $product)
                    <div class="bg-white border border-gray-200 rounded-sm hover:shadow-lg hover:border-brand-500 transition-all duration-200 flex flex-col group">
                        
                        <!-- Image -->
                        <a href="{{ route('shop.show', $product) }}" class="block relative overflow-hidden bg-gray-100 aspect-square">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs">No Image</div>
                            @endif
                            
                            <!-- Badge: Stok menipis -->
                            @if($product->stock > 0 && $product->stock <= 5)
                                <div class="absolute top-0 left-0 bg-orange-500 text-white text-[10px] font-bold px-1 py-1 rounded-br-sm">
                                    Sisa {{ $product->stock }}
                                </div>
                            @elseif($product->stock == 0)
                                <div class="absolute top-0 left-0 bg-red-500 text-white text-[10px] font-bold px-1 py-1 rounded-br-sm">
                                    Habis
                                </div>
                            @endif
                        </a>

                        <!-- Info -->
                        <div class="p-2 flex-1 flex flex-col">
                            <a href="{{ route('shop.show', $product) }}" class="text-xs text-gray-700 line-clamp-2 mb-1 min-h-[2.5rem]">{{ $product->name }}</a>
                            
                            <div class="mt-auto">
                                <div class="text-brand-600 font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="flex items-center justify-between mt-1">
                                    <div class="flex items-center text-[10px] text-gray-400">
                                        <svg class="w-3 h-3 mr-0.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        4.8
                                    </div>
                                    @php
                                        $totalSold = \App\Models\TransactionDetail::where('product_id', $product->id)->sum('quantity');
                                    @endphp
                                    <span class="text-[10px] text-gray-400">{{ $totalSold }} terjual</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-500">Belum ada produk tersedia.</div>
        @endif
    </div>

</x-public-layout>