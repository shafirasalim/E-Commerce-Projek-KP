@props([
    'title',
    'value',
    'color' => 'blue',
    'icon' => 'box'
])

@php
    $colors = [
        'green' => 'border-green-500 bg-green-100 text-green-600',
        'blue' => 'border-blue-500 bg-blue-100 text-blue-600',
        'brand' => 'border-brand-500 bg-brand-100 text-brand-600',
        'purple' => 'border-purple-500 bg-purple-100 text-purple-600',
        'yellow' => 'border-yellow-500 bg-yellow-100 text-yellow-600',
    ];

    $icons = [
        'money' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'cart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>',
        'clock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'box' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>',
    ];
@endphp

<div class="bg-white rounded-xl shadow-sm p-6 border-l-4 {{ $colors[$color] ?? $colors['blue'] }}">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 {{ $colors[$color] ?? $colors['blue'] }} rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icons[$icon] ?? $icons['box'] !!}
            </svg>
        </div>
    </div>
</div>