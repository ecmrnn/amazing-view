@props([
    'role' => '',
    'type' => '',
])

@php
    $label = '';
    $class = '';
    switch ($role) {
        case 0:
            $label = 'Guest';
            $class = 'text-white border-green-500 bg-green-500/75';
            break;
        case 1:
            $label = 'Receptionist'; /* Frontdesk */
            $class = 'text-white border-blue-500 bg-blue-500/75';
            break;
        case 2:
            $label = 'Admin';
            $class = 'text-white border-amber-500 bg-amber-500/75';
            break;
        default:
            # code...
            break;
    }
@endphp

<strong class="px-3 py-1 text-xs font-semibold border rounded-full {{ $class }}">
    {{ $label }}
</strong>
