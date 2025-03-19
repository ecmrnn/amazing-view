@props([
    'role' => '',
    'type' => '',
])

@php
    // Role Labels and Tailwind Classes
    $roleLabels = [
        1 => ['Guest', 'text-green-800 border-green-500 bg-green-50'],
        2 => ['Receptionist', 'text-blue-800 border-blue-500 bg-blue-50'],
        3 => ['Admin', 'text-amber-800 border-amber-500 bg-amber-50'],
    ];

    // Default values if the role is not found
    $default = ['Unknown', 'text-gray-800 border-gray-500 bg-gray-200'];

    // Assign label and class dynamically
    [$label, $class] = $roleLabels[$role] ?? $default;
@endphp

<strong class="px-3 py-1 text-xs font-semibold border rounded-full {{ $class }}">
    {{ $label }}
</strong>
