@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)
@section('header', 'Detail Pesanan')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-brand-600 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Informasi Pesanan & Produk -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header Pesanan -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Pesanan #{{ $order->id }}</h2>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($order->status == 'paid') bg-green-100 text-green-800
                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Produk Dipesan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $detail->product->name ?? 'Produk Dihapus' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-center">{{ $detail->quantity }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-900">Total Pembayaran</td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-brand-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Info Customer & Aksi -->
        <div class="space-y-6">
            <!-- Info Customer -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelanggan</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500 block">Nama</span>
                        <span class="font-medium text-gray-900">{{ $order->user->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Email</span>
                        <span class="font-medium text-gray-900">{{ $order->user->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Tanggal Pesanan</span>
                        <span class="font-medium text-gray-900">{{ $order->transaction_date ? \Carbon\Carbon::parse($order->transaction_date)->format('d M Y, H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <select name="status" class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        Simpan Perubahan Status
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection