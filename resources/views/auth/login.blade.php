<x-guest-layout>
    <!-- Logo & Brand -->
    <div class="mb-8 text-center">
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-brand-600">CIANJUR FRESH</h1>
        <p class="text-gray-600 mt-2">Selamat datang kembali!</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500">
                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand-600 hover:text-brand-700 font-medium" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-200">
            Masuk
        </button>

        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-brand-600 hover:text-brand-700 font-semibold">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </form>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>&copy; {{ date('Y') }} Cianjur Fresh. All rights reserved.</p>
    </div>
</x-guest-layout>