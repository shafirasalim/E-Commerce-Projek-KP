@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('header', 'Kelola Pesanan')

@section('content')
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="flex overflow-x-auto border-b border-gray-200">
            @php
                $tabs = [
                    'all' => 'Semua',
                    'paid' => 'Dibayar',
                    'shipped' => 'Dikirim',
                    'completed' => 'Selesai',
                    'cancelled' => 'Batal',
                ];
                $activeTab = request('status', 'all');
            @endphp
            
            @foreach($tabs as $key => $label)
                <a href="{{ route('admin.orders.index', ['status' => $key]) }}" 
                   class="flex-1 min-w-[120px] py-4 px-3 text-center text-sm font-medium border-b-2 transition whitespace-nowrap
                   {{ $activeTab === $key ? 'border-brand-600 text-brand-600 bg-brand-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Pesanan</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #INV-{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                                <div class="text-sm text-gray-500">{{ $order->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-brand-600 hover:text-brand-900">
                                    Detail
                                </a>
                                
                                <!-- Update Status Form -->
                                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-md focus:ring-brand-500 focus:border-brand-500">
                                            <option value="">Ubah Status</option>
                                            @if($order->status === 'pending')
                                                <option value="paid">Tandai Dibayar</option>
                                                <option value="cancelled">Batalkan</option>
                                            @elseif($order->status === 'paid')
                                                <option value="shipped">Tandai Dikirim</option>
                                                <option value="cancelled">Batalkan</option>
                                            @elseif($order->status === 'shipped')
                                                <option value="completed">Tandai Selesai</option>
                                            @endif
                                        </select>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada pesanan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection