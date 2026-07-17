<x-public-layout title="Detail Pesanan #INV-{{ $order->id }}">
    
    <div class="bg-gray-50 min-h-screen pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-brand-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Pesanan Saya
                </a>
            </div>

            <!-- Order Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                        <p class="text-sm text-gray-500 mt-1">No. Pesanan: <span class="font-medium text-gray-900">#INV-{{ $order->id }}</span></p>
                        <p class="text-sm text-gray-500">Tanggal: {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-blue-100 text-blue-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'pending' => 'Menunggu Pembayaran',
                            'paid' => 'Sudah Dibayar',
                            'shipped' => 'Dikirim',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Produk yang Dipesan</h2>
                
                <div class="space-y-4">
                    @foreach($order->details as $detail)
                        <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($detail->product && $detail->product->image)
                                    <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 mb-1">{{ $detail->product->name ?? 'Produk tidak tersedia' }}</h3>
                                <p class="text-xs text-gray-500 mb-2">Jumlah: {{ $detail->quantity }} item</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">Rp {{ number_format($detail->price, 0, ',', '.') }} x {{ $detail->quantity }}</span>
                                    <span class="text-sm font-semibold text-brand-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal Produk</span>
                        <span class="text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="text-gray-900">Rp 0</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between">
                        <span class="text-base font-semibold text-gray-900">Total Pembayaran</span>
                        <span class="text-xl font-bold text-brand-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($order->payment)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="text-gray-900 font-medium">
                                @if($order->payment->method === 'bank_transfer')
                                    Transfer Bank
                                @elseif($order->payment->method === 'ewallet')
                                    E-Wallet
                                @elseif($order->payment->method === 'cod')
                                    Bayar di Tempat (COD)
                                @else
                                    {{ $order->payment->method }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="text-gray-900 font-medium">{{ ucfirst($order->payment->status) }}</span>
                        </div>
                        @if($order->payment->paid_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Pembayaran</span>
                                <span class="text-gray-900">{{ \Carbon\Carbon::parse($order->payment->paid_at)->format('d M Y, H:i') }} WIB</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                @if($order->status === 'pending')
                    <a href="{{ route('payment.redirect', $order->id) }}" class="flex-1 bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-6 rounded-lg text-center transition">
                        Bayar Sekarang
                    </a>
                @endif
                
                <a href="{{ route('orders.index') }}" class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg text-center transition">
                    Kembali ke Pesanan
                </a>
            </div>

        </div>
    </div>

</x-public-layout>