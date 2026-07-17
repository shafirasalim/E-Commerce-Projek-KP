<x-app-layout title="Pesanan Saya">
    
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Pesanan Saya</h1>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                <div class="flex overflow-x-auto border-b border-gray-200">
                    @php
                        $tabs = [
                            'all' => 'Semua',
                            'paid' => 'Sudah Dibayar',
                            'shipped' => 'Dikirim',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                        $activeTab = request('status', 'all');
                    @endphp
                    
                    @foreach($tabs as $key => $label)
                        <a href="{{ route('orders.index', ['status' => $key]) }}" 
                           class="flex-1 min-w-[120px] py-4 px-3 text-center text-sm font-medium border-b-2 transition whitespace-nowrap
                           {{ $activeTab === $key ? 'border-brand-600 text-brand-600 bg-brand-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Orders List -->
            <div class="space-y-4">
                @forelse($orders as $order)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">No. Pesanan #INV-{{ $order->id }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'paid' => 'bg-green-100 text-green-800',
                                    'shipped' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'paid' => 'Dibayar',
                                    'shipped' => 'Dikirim',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Batal',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </div>
                        
                        <div class="p-6">
                            @foreach($order->details as $detail)
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if($detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->name }}" class="w-20 h-20 rounded-lg object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $detail->product->name }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">Jumlah: {{ $detail->quantity }}</p>
                                        <p class="text-sm font-semibold text-brand-600 mt-1">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Pembayaran</p>
                                <p class="text-lg font-bold text-brand-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('orders.show', $order->id) }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-gray-600">Belum ada pesanan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </div>

</x-app-layout>