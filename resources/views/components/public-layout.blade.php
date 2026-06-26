<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PT Nama Perusahaan' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-sm fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-brand-600">
                        BRAND LOGO
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Beranda</a>
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Tentang Kami</a>
                    <a href="{{ route('shop.index') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Katalog</a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-brand-600 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-brand-600 font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-md font-medium transition">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-brand-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-2 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Beranda</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Tentang Kami</a>
                <a href="{{ route('shop.index') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Katalog</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-brand-600 hover:bg-brand-50 rounded">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="pt-16">
        {{ $slot }}
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold text-brand-500 mb-4">BRAND LOGO</h3>
                    <p class="text-gray-400 text-sm">Menyediakan produk berkualitas untuk kebutuhan Anda.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-brand-500">Beranda</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-brand-500">Tentang Kami</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-brand-500">Katalog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>info@perusahaan.com</li>
                        <li>+62 812 3456 7890</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} PT Nama Perusahaan. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Script untuk Mobile Menu -->
    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>