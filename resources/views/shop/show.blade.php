<x-public-layout :title="$product->name">

    <style>
        .img-square {
            display: block;
            position: relative;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            background-color: #f3f4f6;
        }
        .img-square img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
    
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-brand-600">Home</a></li>
                    <li>/</li>
                    <li><a href="{{ route('shop.index') }}" class="hover:text-brand-600">Katalog</a></li>
                    <li>/</li>
                    <li class="text-gray-900">{{ $product->name }}</li>
                </ol>
            </nav>

            <!-- Product Detail -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <!-- Product Image -->
                <div>
                    @if($product->image)
                        <div class="img-square rounded-lg shadow-lg">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        </div>
                    @else
                        <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-400 text-lg">No Image</span>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div>
                    <span class="text-sm text-brand-600 font-medium">{{ $product->category->name }}</span>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $product->name }}</h1>

                    <div class="mt-4">
                        <span class="text-4xl font-bold text-brand-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mt-6">
                        @if($product->stock > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Stok Tersedia: {{ $product->stock }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Stok Habis
                            </span>
                        @endif
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Produk</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $product->description ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>

                    <!-- Add to Cart -->
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border border-gray-300 rounded-md">
                                    <button type="button" onclick="decreaseQty()" class="px-3 py-2 text-gray-600 hover:text-brand-600">-</button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                        class="w-16 text-center border-0 focus:ring-0">
                                    <button type="button" onclick="increaseQty()" class="px-3 py-2 text-gray-600 hover:text-brand-600">+</button>
                                </div>

                                <button type="submit" class="flex-1 bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-6 rounded-md transition">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-300 text-gray-500 font-medium py-3 px-6 rounded-md cursor-not-allowed mt-8">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Produk Terkait</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $related)
                            <a href="{{ route('shop.show', $related) }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
                                @if($related->image)
                                    <div class="img-square" style="aspect-ratio: 4 / 3;">
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}">
                                    </div>
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">No Image</span>
                                    </div>
                                @endif
                                <div class="p-3">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $related->name }}</h3>
                                    <p class="text-brand-600 font-bold mt-1">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        function decreaseQty() {
            const input = document.getElementById('quantity');
            const value = parseInt(input.value);
            if (value > 1) input.value = value - 1;
        }

        function increaseQty() {
            const input = document.getElementById('quantity');
            const value = parseInt(input.value);
            const max = parseInt(input.max);
            if (value < max) input.value = value + 1;
        }
    </script>

</x-public-layout>