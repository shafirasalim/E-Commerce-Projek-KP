<x-app-layout title="Kelola Akun">
    
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Kelola Akun Saya</h1>

            <!-- Flash Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="space-y-8">
                
                <!-- Form Informasi Profil -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Profil</h2>
                        <p class="text-sm text-gray-500 mt-1">Perbarui informasi akun dan alamat pengiriman Anda.</p>
                    </div>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Nomor HP -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp / HP</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone_number ?? '') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                                    placeholder="081234567890">
                                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Role (Read Only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Akun</label>
                                <input type="text" value="{{ ucfirst($user->role->nama_role ?? 'Customer') }}" disabled
                                    class="block w-full px-4 py-3 rounded-lg bg-gray-100 border-gray-200 text-gray-500 cursor-not-allowed">
                                <p class="mt-1 text-xs text-gray-500">Status akun tidak dapat diubah secara manual.</p>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                            <textarea name="address" id="address" rows="3"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                                placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan...">{{ old('address', $user->address ?? '') }}</textarea>
                            @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Form Ubah Password -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
                        <p class="text-sm text-gray-500 mt-1">Pastikan akun Anda aman dengan password yang kuat.</p>
                    </div>
                    
                    <form action="{{ route('password.update') }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="max-w-md space-y-4">
                            <!-- Password Lama -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" id="current_password" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                                @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Password Baru -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" id="password" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>