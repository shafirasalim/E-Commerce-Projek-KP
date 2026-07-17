<x-public-layout title="Pembayaran Berhasil">
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Success Card -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <!-- Success Icon -->
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">Pembayaran Berhasil!</h1>
                <p class="text-gray-600 mb-8">
                    Terima kasih telah berbelanja. Pesanan Anda sedang diproses.
                </p>

                <!-- Order Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <div class="text-sm text-gray-600 mb-2">Nomor Pesanan</div>
                    <div class="text-2xl font-bold text-gray-900 mb-4">#INV-{{ $transaction->id }}</div>
                    <div class="text-sm text-gray-600 mb-2">Total Pembayaran</div>
                    <div class="text-3xl font-bold text-brand-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('orders.show', $transaction->id) }}" class="flex-1 bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-6 rounded-lg transition">
                        Lihat Detail Pesanan
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg transition">
                        Lihat Semua Pesanan
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-public-layout>