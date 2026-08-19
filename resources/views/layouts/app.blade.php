<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cianjur Fresh' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- PERBAIKAN LOGO DI SINI -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('images/logomarkisa.png') }}" alt="Logo Cianjur Fresh" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-200">
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
                    
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = ! open" class="flex items-center text-gray-600 hover:text-brand-600 font-medium focus:outline-none">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Beranda</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                            
                            <!-- Tambahan: Pengaturan Akun -->
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan Akun</a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
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
                <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Keranjang</a>
                <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Pesanan Saya</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Pengaturan Akun</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow pt-16">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @else
        <div class="pt-16"></div>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

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