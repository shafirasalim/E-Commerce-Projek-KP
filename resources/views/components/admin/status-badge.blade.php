@props(['status'])

@php
    $statuses = [
        'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-brand-100 text-brand-800'],
        'paid' => ['label' => 'Dibayar', 'color' => 'bg-green-100 text-green-800'],
        'shipped' => ['label' => 'Dikirim', 'color' => 'bg-blue-100 text-blue-800'],
        'completed' => ['label' => 'Selesai', 'color' => 'bg-green-100 text-green-800'],
        'cancelled' => ['label' => 'Batal', 'color' => 'bg-red-100 text-red-800'],
    ];

    $statusData = $statuses[$status] ?? ['label' => ucfirst($status), 'color' => 'bg-gray-100 text-gray-800'];
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusData['color'] }}">
    {{ $statusData['label'] }}
</span>