<x-public-layout title="Proses Pembayaran">
    <div class="bg-white py-12 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Selesaikan Pembayaran</h1>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order #INV-{{ $transaction->id }}</h2>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-brand-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-4 px-8 rounded-lg text-lg shadow-lg transition">
                Bayar Sekarang
            </button>

        </div>
    </div>

    <!-- Script Midtrans Snap - CLIENT KEY DI-HARDCODE! -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="Mid-client-XEzG1LZy8SVjLE0N"></script>
    <script>
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route("payment.success", $transaction->id) }}';
                },
                onPending: function(result) {
                    alert('Pembayaran pending, silakan selesaikan pembayaran');
                    window.location.href = '{{ route("payment.success", $transaction->id) }}';
                },
                onError: function(result) {
                    alert('Terjadi kesalahan saat pembayaran');
                },
                onClose: function() {
                    alert('Anda menutup popup pembayaran tanpa menyelesaikan');
                }
            });
        };
    </script>
</x-public-layout>