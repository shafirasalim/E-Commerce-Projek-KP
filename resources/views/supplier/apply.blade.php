<x-public-layout title="Daftar Supplier">
    
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-brand-500 to-brand-600 p-6 text-white">
                    <h1 class="text-2xl font-bold">Daftar sebagai Supplier</h1>
                    <p class="mt-2 opacity-90">Bergabunglah dengan Cianjur Fresh untuk memasarkan produk Anda.</p>
                </div>

                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(isset($existingApplication))
                        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded">
                            <p class="font-semibold">Status Aplikasi Anda: 
                                <span class="uppercase">{{ $existingApplication->status }}</span>
                            </p>
                            <p class="text-sm mt-1">Diajukan pada: {{ $existingApplication->created_at->format('d M Y') }}</p>
                            @if($existingApplication->status === 'pending')
                                <p class="text-sm mt-2">Tim kami sedang meninjau aplikasi Anda. Mohon tunggu.</p>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('supplier.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan / Toko</label>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                                    placeholder="081234567890">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Produk / Usaha</label>
                                <textarea name="description" id="description" rows="4" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm"
                                    placeholder="Ceritakan tentang produk atau usaha Anda...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition">
                                Kirim Aplikasi
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-public-layout>