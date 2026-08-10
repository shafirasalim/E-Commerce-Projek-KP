<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cianjur Fresh' }}</title>
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
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <!-- Logo -->
                        <img src="{{ asset('images/logomarkisa.png') }}" alt="Logo Cianjur Fresh" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-200">
                        <!-- Teks -->
                        <span class="text-2xl font-bold text-brand-600">CIANJUR FRESH</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Beranda</a>
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Tentang Kami</a>
                    <a href="{{ route('shop.index') }}" class="text-gray-600 hover:text-brand-600 font-medium transition">Katalog</a>
                    
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-brand-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        @php
                            $cartCount = 0;
                            if (Auth::check()) {
                                $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
                                $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                            } else {
                                $cart = session()->get('cart', []);
                                $cartCount = collect($cart)->sum('quantity');
                            }
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    
                    @auth
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = ! open" class="flex items-center text-gray-600 hover:text-brand-600 font-medium focus:outline-none">
                                {{ Auth::user()->name }}
                                @if(Auth::user()->role && Auth::user()->role->nama_role === 'supplier')
                                    <span class="ml-2 px-2 py-1 text-xs bg-brand-100 text-brand-800 rounded-full">Supplier</span>
                                @endif
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Beranda</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan Akun</a>
                                
                                @if(Auth::user()->role && Auth::user()->role->nama_role === 'supplier')
                                    <hr class="my-1">
                                    <div class="px-4 py-2 text-xs text-green-700 bg-green-50">✓ Akun Supplier Aktif</div>
                                @else
                                    <a href="{{ route('supplier.apply') }}" class="block px-4 py-2 text-sm text-brand-600 hover:bg-gray-100 font-medium">Daftar Supplier</a>
                                @endif
                                
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
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
                
                <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">
                    Keranjang
                    @if($cartCount > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1">{{ $cartCount }}</span>
                    @endif
                </a>
                
                @auth
                    <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Pesanan Saya</a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Pengaturan Akun</a>
                    @if(Auth::user()->role && Auth::user()->role->nama_role !== 'supplier')
                        <a href="{{ route('supplier.apply') }}" class="block px-3 py-2 text-brand-600 hover:bg-brand-50 rounded font-medium">Daftar Supplier</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Logout</button>
                    </form>
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

    <!-- FOOTER (DIPERBAIKI: 4 KOLOM + LINK SHOPEE) -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Kolom 1: Brand -->
                <div>
                    <h3 class="text-xl font-bold text-brand-500 mb-4 flex items-center gap-2">
                        <img src="{{ asset('images/logomarkisa.png') }}" alt="Logo" class="h-8 w-auto object-contain">
                        CIANJUR FRESH
                    </h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Menyediakan produk berkualitas, segar, dan langsung dari petani lokal Cianjur untuk kebutuhan Anda.</p>
                </div>
                
                <!-- Kolom 2: Tautan -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-brand-500 transition">Beranda</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-brand-500 transition">Tentang Kami</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-brand-500 transition">Katalog</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            info@ciajurfresh.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            +62 812 3456 7890
                        </li>
                    </ul>
                </div>

                <!-- Kolom 4: Belanja Online (BARU) -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Belanja Online</h4>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="https://s.shopee.co.id/8KobEL9bOb" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 text-gray-400 hover:text-orange-500 transition group">
                                <!-- Shopee Icon -->
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19.552 16.248l-1.952-1.488c-0.24-0.192-0.592-0.144-0.784 0.096l-2.384 3.024c-0.416-0.768-0.944-1.472-1.584-2.08l2.368-3.024c0.192-0.24 0.144-0.592-0.096-0.784l-1.952-1.488c-0.24-0.192-0.592-0.144-0.784 0.096l-2.384 3.04c-0.608-0.128-1.248-0.192-1.888-0.192-0.656 0-1.296 0.064-1.92 0.192l-2.368-3.04c-0.192-0.24-0.544-0.288-0.784-0.096l-1.952 1.488c-0.24 0.192-0.288 0.544-0.096 0.784l2.368 3.024c-0.64 0.608-1.168 1.312-1.584 2.08l-2.384-3.024c-0.192-0.24-0.544-0.288-0.784-0.096l-1.952 1.488c-0.24 0.192-0.288 0.544-0.096 0.784l2.832 3.616c-0.4 0.928-0.624 1.952-0.624 3.024 0 4.416 3.584 8 8 8s8-3.584 8-8c0-1.072-0.224-2.096-0.624-3.024l2.832-3.616c0.192-0.24 0.144-0.592-0.096-0.784zM12 20c-2.208 0-4-1.792-4-4 0-2.208 1.792-4 4-4s4 1.792 4 4c0 2.208-1.792 4-4 4z"/>
                                </svg>
                                <span class="font-medium">Shopee Store</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Cianjur Fresh. All rights reserved.
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

    <!-- Alpine.js untuk Dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>