@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('header', 'Laporan Penjualan')

@section('content')

    <div class="bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Laporan Penjualan</h1>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.download', ['format' => 'pdf', 'from' => $from, 'to' => $to]) }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium transition">
                        📄 Download PDF
                    </a>
                    <a href="{{ route('admin.reports.download', ['format' => 'csv', 'from' => $from, 'to' => $to]) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition">
                        📊 Download Excel (CSV)
                    </a>
                </div>
            </div>

            <!-- Filter Tanggal -->
            <form method="GET" action="{{ route('admin.reports.index') }}"
                  class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-4 flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ $from }}" class="rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ $to }}" class="rounded-md border-gray-300">
                </div>
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2 rounded-md font-medium transition">
                    Tampilkan
                </button>
            </form>

            <!-- Kartu Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-green-50 border border-green-200 rounded-lg p-5">
                    <p class="text-sm text-green-700 mb-1">Total Omzet (Paid)</p>
                    <p class="text-2xl font-bold text-green-800">Rp {{ number_format($summary['omzet'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-5">
                    <p class="text-sm text-blue-700 mb-1">Total Transaksi</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $summary['total_transaksi'] }}</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5">
                    <p class="text-sm text-yellow-700 mb-1">Transaksi Paid</p>
                    <p class="text-2xl font-bold text-yellow-800">{{ $summary['total_paid'] }}</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-5">
                    <p class="text-sm text-purple-700 mb-1">Produk Terjual</p>
                    <p class="text-2xl font-bold text-purple-800">{{ $summary['produk_terjual'] }}</p>
                </div>
            </div>

            <!-- Tabel Transaksi -->
            <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $t)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">#INV-{{ $t->id }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $t->user->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $t->details->sum('quantity') }}</td>
                                <td class="px-4 py-3">
                                    @if($t->status === 'paid')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Dibayar</span>
                                    @elseif($t->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">{{ ucfirst($t->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-brand-600">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection