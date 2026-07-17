<x-public-layout title="Dashboard Supplier">
    
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-lg p-6 mb-6 text-white">
                <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 🎉</h1>
                <p class="opacity-90">Anda sekarang adalah Supplier Cianjur Fresh. Kelola produk Anda dari sini.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-500">
                    <p class="text-sm text-gray-500 mb-1">Total Produk Saya</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 mb-1">Pesanan Masuk</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 mb-1">Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp 0</p>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Supplier Anda</h2>
                <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-green-800 font-semibold">✓ Akun Supplier Aktif</p>
                    <p class="text-sm text-green-700 mt-1">Anda dapat mulai menambahkan produk untuk dijual di Cianjur Fresh.</p>
                </div>
            </div>

        </div>
    </div>

</x-public-layout>