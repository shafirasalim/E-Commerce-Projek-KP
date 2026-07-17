@extends('layouts.admin')

@section('title', 'Kelola Supplier')
@section('header', 'Kelola Supplier')

@section('content')
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="flex overflow-x-auto border-b border-gray-200">
            @php
                $tabs = [
                    'all' => 'Semua',
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ];
                $activeTab = request('status', 'all');
            @endphp
            
            @foreach($tabs as $key => $label)
                <a href="{{ route('admin.suppliers.index', ['status' => $key]) }}" 
                   class="flex-1 min-w-[120px] py-4 px-3 text-center text-sm font-medium border-b-2 transition whitespace-nowrap
                   {{ $activeTab === $key ? 'border-brand-600 text-brand-600 bg-brand-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Aplikasi Supplier</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Perusahaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Apply</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                #{{ $app->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $app->company_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $app->user->name ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $app->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $app->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-brand-100 text-brand-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$app->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$app->status] ?? $app->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($app->status === 'pending')
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('admin.suppliers.approve', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Setujui aplikasi supplier ini? Role user akan diubah menjadi Supplier.')">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium transition">
                                                ✓ Approve
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.suppliers.reject', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak aplikasi supplier ini?')">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium transition">
                                                ✗ Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <a href="{{ route('admin.suppliers.show', $app->id) }}" class="text-brand-600 hover:text-brand-900">
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada aplikasi supplier
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $applications->links() }}
            </div>
        @endif
    </div>

@endsection