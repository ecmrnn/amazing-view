@props([
    'role' => '',
    'type' => '',
])

@php
    $label = '';
    $class = '';
    switch ($role) {
        case 1:
            $label = 'Guest';
            $class = 'text-green-800 border-green-500 bg-green-50';
            break;
        case 2:
            $label = 'Receptionist';
            $class = 'text-blue-800 border-blue-500 bg-blue-50';
            break;
        case 3:
            $label = 'Admin';
            $class = 'text-amber-800 border-amber-500 bg-amber-50';
            break;
    }
@endphp

<strong class="px-3 py-1 text-xs font-semibold border rounded-full {{ $class }}">
    {{ $label }}
</strong>
