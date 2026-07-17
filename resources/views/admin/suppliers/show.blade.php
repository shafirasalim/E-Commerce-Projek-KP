@extends('layouts.admin')

@section('title', 'Detail Supplier')
@section('header', 'Detail Aplikasi Supplier')

@section('content')
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-brand-600 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Supplier
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Company Info Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-brand-500 to-brand-600 p-6 text-white">
                    <h2 class="text-2xl font-bold">{{ $application->company_name }}</h2>
                    <p class="mt-2 opacity-90">Aplikasi Supplier</p>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Deskripsi Produk / Usaha</label>
                        <p class="mt-1 text-gray-900">{{ $application->description }}</p>
                    </div>
                    
                    @if($application->phone)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nomor Telepon / WhatsApp</label>
                            <p class="mt-1 text-gray-900">{{ $application->phone }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                        <p class="mt-1 text-gray-900">{{ $application->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    
                    @if($application->reviewed_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Review</label>
                            <p class="mt-1 text-gray-900">{{ $application->reviewed_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Aplikasi</h3>
                
                @php
                    $statusColors = [
                        'pending' => 'bg-brand-100 text-brand-800 border-brand-500',
                        'approved' => 'bg-green-100 text-green-800 border-green-500',
                        'rejected' => 'bg-red-100 text-red-800 border-red-500',
                    ];
                    $statusLabels = [
                        'pending' => 'Pending Review',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ];
                @endphp
                
                <div class="mb-4 p-4 rounded-lg border-2 {{ $statusColors[$application->status] }}">
                    <p class="text-sm font-medium">Status Saat Ini</p>
                    <p class="text-xl font-bold mt-1">{{ $statusLabels[$application->status] }}</p>
                </div>

                @if($application->status === 'pending')
                    <div class="space-y-3">
                        <form action="{{ route('admin.suppliers.approve', $application->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                                ✓ Setujui Aplikasi
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.suppliers.reject', $application->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak aplikasi ini?')">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                                ✗ Tolak Aplikasi
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg text-center text-sm text-gray-600">
                        Aplikasi ini sudah direview
                    </div>
                @endif
            </div>

            <!-- Applicant Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemilik</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama</label>
                        <p class="text-gray-900 font-medium">{{ $application->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $application->user->email ?? '-' }}</p>
                    </div>
                    @if($application->user->phone_number)
                        <div>
                            <label class="text-sm font-medium text-gray-500">No. Telepon</label>
                            <p class="text-gray-900">{{ $application->user->phone_number }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

@endsection