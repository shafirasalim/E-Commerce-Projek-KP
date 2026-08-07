<x-public-layout title="Keranjang Belanja">

    <style>
        .img-thumb {
            width: 6rem;
            height: 6rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
            overflow: hidden;
            flex-shrink: 0;
        }
        .img-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>
    
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

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

            @if($cartItems->count() > 0)
                <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Cart Items -->
                        <div class="lg:col-span-2">
                            <div class="bg-white border border-gray-200 rounded-lg" id="cart-items-container">
                                @foreach($cartItems as $index => $item)
                                    <div class="p-6 border-b border-gray-200 last:border-b-0" id="item-{{ $item['product_id'] }}">
                                        
                                        <div class="flex items-start gap-4">
                                            
                                            <!-- Checkbox -->
                                            <div class="flex-shrink-0 pt-2">
                                                <input type="checkbox" name="selected_items[]" value="{{ $item['product_id'] }}" 
                                                    class="w-5 h-5 text-brand-600 border-gray-300 rounded focus:ring-brand-500 item-checkbox cursor-pointer" 
                                                    data-price="{{ $item['price'] }}" 
                                                    data-quantity="{{ $item['quantity'] }}"
                                                    {{ $index === 0 ? 'checked' : '' }}>
                                            </div>
                                            
                                            <!-- Product Image -->
                                            <div class="img-thumb">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                                @endif
                                            </div>

                                            <!-- Product Info -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $item['name'] }}</h3>
                                                <p class="text-brand-600 font-bold mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                                
                                                <div class="mt-2">
                                                    <a href="{{ route('checkout.buy_now', $item['product_id']) }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                                                        Beli Sekarang
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Quantity & Actions -->
                                            <div class="flex-shrink-0 flex items-center gap-3">
                                                <div class="flex items-center border border-gray-300 rounded-md">
                                                    <button type="button" onclick="updateQuantity({{ $item['product_id'] }}, 'decrease')" 
                                                        class="px-3 py-2 text-gray-600 hover:text-brand-600 hover:bg-gray-50 transition">
                                                        -
                                                    </button>
                                                    <span class="px-4 py-2 border-x border-gray-300 min-w-[3rem] text-center" id="qty-{{ $item['product_id'] }}">
                                                        {{ $item['quantity'] }}
                                                    </span>
                                                    <button type="button" onclick="updateQuantity({{ $item['product_id'] }}, 'increase')" 
                                                        class="px-3 py-2 text-gray-600 hover:text-brand-600 hover:bg-gray-50 transition">
                                                        +
                                                    </button>
                                                </div>

                                                <button type="button" onclick="removeItem({{ $item['product_id'] }})" 
                                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition" 
                                                    title="Hapus dari keranjang">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 sticky top-20">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                                
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal (<span id="selected-count">0</span> item)</span>
                                        <span>Rp <span id="selected-total">0</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Ongkos Kirim</span>
                                        <span>Dihitung saat checkout</span>
                                    </div>
                                    <div class="border-t border-gray-300 pt-3 flex justify-between text-lg font-bold text-gray-900">
                                        <span>Total</span>
                                        <span class="text-brand-600">Rp <span id="final-total">0</span></span>
                                    </div>
                                </div>

                            @auth
                                <button type="submit" form="checkout-form" class="block w-full bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-6 rounded-md text-center transition">
                                    Checkout Pilihan
                                </button>
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.index')) }}" class="block w-full bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-6 rounded-md text-center transition">
                                    Login untuk Checkout
                                </a>
                            @endauth

                                <a href="{{ route('shop.index') }}" class="block w-full mt-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-md text-center transition">
                                    Lanjut Belanja
                                </a>
                            </div>
                        </div>

                    </div>
                </form>
            @else
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg mb-6">Keranjang Anda masih kosong</p>
                    <a href="{{ route('shop.index') }}" class="inline-block bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-8 rounded-md transition">
                        Mulai Belanja
                    </a>
                </div>
            @endif

        </div>
    </div>

    <script>
        const isUpdating = {};

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        function updateQuantity(productId, action) {
            if (isUpdating[productId]) return;
            isUpdating[productId] = true;

            const qtyElement = document.getElementById('qty-' + productId);
            let currentQty = parseInt(qtyElement.textContent);
            
            let newQty;
            if (action === 'increase') {
                newQty = currentQty + 1;
            } else {
                newQty = Math.max(1, currentQty - 1);
            }

            fetch('{{ route('cart.update', 'PRODUCT_ID') }}'.replace('PRODUCT_ID', productId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: newQty,
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    qtyElement.textContent = newQty;
                    
                    const checkbox = document.querySelector(`input[value="${productId}"]`);
                    if (checkbox) {
                        checkbox.dataset.quantity = newQty;
                    }
                    
                    calculateTotal();
                    
                    showToast('Keranjang berhasil diupdate!');
                } else {
                    showToast(data.message || 'Gagal update keranjang', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat update keranjang', 'error');
            })
            .finally(() => {
                isUpdating[productId] = false;
            });
        }

        function removeItem(productId) {
            if (isUpdating[productId]) return;
            isUpdating[productId] = true;

            fetch('{{ route('cart.remove', 'PRODUCT_ID') }}'.replace('PRODUCT_ID', productId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const itemElement = document.getElementById('item-' + productId);
                    
                    if (itemElement) {
                        itemElement.style.transition = 'all 0.3s ease';
                        itemElement.style.opacity = '0';
                        itemElement.style.transform = 'translateX(-20px)';
                        
                        setTimeout(() => {
                            itemElement.remove();
                            calculateTotal();
                            showToast('Produk dihapus dari keranjang!');
                            
                            const container = document.getElementById('cart-items-container');
                            if (container.children.length === 0) {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        }, 300);
                    }
                } else {
                    showToast(data.message || 'Gagal menghapus produk', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menghapus produk', 'error');
            })
            .finally(() => {
                isUpdating[productId] = false;
            });
        }

        function calculateTotal() {
            let total = 0;
            let count = 0;
            
            document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                const price = parseInt(checkbox.dataset.price);
                const quantity = parseInt(checkbox.dataset.quantity);
                total += price * quantity;
                count++;
            });
            
            document.getElementById('selected-count').textContent = count;
            document.getElementById('selected-total').textContent = total.toLocaleString('id-ID');
            document.getElementById('final-total').textContent = total.toLocaleString('id-ID');
        }
        
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });
        
        calculateTotal();
    </script>

</x-public-layout>