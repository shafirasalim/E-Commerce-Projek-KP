<x-public-layout title="Katalog Produk">
    
    <div class="bg-gray-50 min-h-screen pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            <!-- Header -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Katalog Produk</h1>
                <p class="text-gray-500 text-sm mt-1">Semua produk terbaik untuk Anda</p>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Products Grid - Compact dengan Card Design -->
            @if($products->count() > 0)
                <!-- Grid Rapat: 2 HP, 3 tablet, 4 laptop, 5 desktop -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($products as $product)
                        <!-- Card Compact -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:border-brand-300 transition-all duration-200 group">
                            
                            <!-- Image Section -->
                            <a href="{{ route('shop.show', $product) }}" class="block relative overflow-hidden bg-gray-100 aspect-square">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badge -->
                                @if($product->stock > 0 && $product->stock <= 5)
                                    <span class="absolute top-1.5 left-1.5 bg-orange-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                        Sisa {{ $product->stock }}
                                    </span>
                                @elseif($product->stock == 0)
                                    <span class="absolute top-1.5 left-1.5 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                        Habis
                                    </span>
                                @endif
                            </a>

                            <!-- Content Section -->
                            <div class="p-2.5">
                                <!-- Category Badge -->
                                <div class="mb-1.5">
                                    <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">
                                        {{ $product->category->name }}
                                    </span>
                                </div>

                                <!-- Product Name -->
                                <a href="{{ route('shop.show', $product) }}" class="block mb-2">
                                    <h3 class="text-xs text-gray-800 font-medium line-clamp-2 leading-tight min-h-[2.2rem] hover:text-brand-600 transition">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <!-- Price -->
                                <div class="mb-2">
                                    <span class="text-brand-600 font-bold text-sm">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <!-- Add to Cart -->
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium py-1.5 rounded-lg transition flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full bg-gray-200 text-gray-400 text-xs font-medium py-1.5 rounded-lg cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-xl shadow-sm">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada produk tersedia</p>
                </div>
            @endif

        </div>
    </div>

</x-public-layout>