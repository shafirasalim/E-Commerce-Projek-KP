<x-public-layout title="Checkout">

    <style>
        .img-checkout {
            width: 3.5rem;
            height: 3.5rem;
            background-color: #ffffff;
            border-radius: 0.375rem;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid #e5e7eb;
        }
        .img-checkout img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>
    
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                
                @if(!isset($buyNowItem))
                    @foreach($checkoutItems as $item)
                        <input type="hidden" name="selected_items[]" value="{{ $item->product_id ?? $item['product_id'] }}">
                    @endforeach
                @endif
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Form Pengiriman -->
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Pengiriman</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                    <textarea id="address" name="address" rows="3" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                                        placeholder="Jl. Contoh No. 123, RT/RW 001/002">{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                    <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                                        placeholder="Jakarta Selatan">
                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone_number) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                                        placeholder="081234567890">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea id="notes" name="notes" rows="2"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                                        placeholder="Catatan untuk kurir...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 sticky top-20">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                            
                            <div class="space-y-3 mb-6">
                                @foreach($checkoutItems as $item)
                                    @php
                                        $name = $item->product->name ?? $item['name'] ?? 'Produk';
                                        $price = $item->price ?? 0;
                                        $qty = $item->quantity ?? 1;
                                        $image = $item->product->image ?? $item['image'] ?? null;
                                    @endphp
                                    <div class="flex gap-3 items-center">
                                        <div class="img-checkout">
                                            @if($image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-[10px]">No Image</div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-800 truncate">{{ $name }}</p>
                                            <p class="text-xs text-gray-500">{{ $qty }} x Rp {{ number_format($price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($price * $qty, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-300 pt-4 space-y-2">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Ongkos Kirim</span>
                                    <span>Rp 0</span>
                                </div>
                                <div class="border-t border-gray-300 pt-3 flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span class="text-brand-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-6 rounded-md transition">
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

</x-public-layout>