<x-public-layout title="Pembayaran">
    <div class="bg-white py-12 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Pilih Metode Pembayaran</h1>

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Ringkasan Tagihan -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tagihan Anda</h2>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-brand-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="mt-2 text-sm text-gray-500">
                    Order ID: #INV-{{ $transaction->id }}
                </div>
            </div>

            <!-- Form Pilihan Pembayaran -->
            <form action="{{ route('payment.process', $transaction->id) }}" method="POST">
                @csrf
                
                <div class="space-y-4 mb-8">
                    <!-- Bank Transfer -->
                    <label class="block cursor-pointer">
                        <input type="radio" name="payment_method" value="bank_transfer" class="peer sr-only" required>
                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-brand-600 peer-checked:bg-brand-50 hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Transfer Bank</h3>
                                    <p class="text-sm text-gray-500">BCA, Mandiri, BNI (Simulasi)</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- E-Wallet -->
                    <label class="block cursor-pointer">
                        <input type="radio" name="payment_method" value="ewallet" class="peer sr-only">
                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-brand-600 peer-checked:bg-brand-50 hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">E-Wallet</h3>
                                    <p class="text-sm text-gray-500">GoPay, OVO, Dana (Simulasi)</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-4 rounded-lg text-lg shadow-lg transition transform hover:-translate-y-1">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</x-public-layout>