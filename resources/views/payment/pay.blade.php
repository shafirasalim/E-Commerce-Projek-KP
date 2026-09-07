<x-public-layout title="Pembayaran">

    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Memproses Pembayaran…</h1>
            <p class="text-gray-500">Halaman pembayaran terbuka otomatis. Kalau tidak muncul, klik tombol di bawah.</p>
            <button id="pay-btn" class="mt-4 bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-6 rounded-md transition">
                Buka Halaman Pembayaran
            </button>
        </div>
    </div>

    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        const snapToken = @json($snapToken);
        const successUrl = @json($successUrl);
        const ordersUrl = @json($ordersUrl);

        function openPayment() {
            snap.pay(snapToken, {
                onSuccess: function (result) {
                    // Pembayaran sukses (misal: GoPay/Qris langsung lunas)
                    window.location.href = successUrl;
                },
                onPending: function (result) {
                    // Pembayaran pending (misal: belum transfer VA)
                    // Arahkan ke daftar pesanan agar user tau harus transfer
                    window.location.href = ordersUrl;
                },
                onError: function (result) {
                    // Pembayaran gagal/error
                    window.location.href = ordersUrl;
                },
                onClose: function () {
                    // User menutup popup Midtrans secara manual
                    window.location.href = ordersUrl;
                }
            });
        }

        document.getElementById('pay-btn').addEventListener('click', openPayment);

        // Otomatis buka popup saat halaman dimuat (dengan sedikit delay biar smooth)
        setTimeout(function() {
            openPayment();
        }, 500);
    </script>

</x-public-layout>