@props([
    'status'
])

@php
    switch ($status) {
        case 0:
            $label = 'Confirmed';
            $class = "text-white border-green-500 bg-green-500/75";
            break;
        case 1:
            $label = 'Pending';
            $class = "text-white border-orange-500 bg-orange-500/75";
            break;
        case 2:
            $label = 'Expired';
            $class = "text-white border-red-500 bg-red-500/75";
            break;
        case 3:
            $label = 'Checked-in';
            $class = "text-white border-blue-500 bg-blue-500/75";
            break;
        case 4:
            $label = 'Checked-out';
            $class = "text-white border-gray-500 bg-gray-500/75";
            break;
        case 5:
            $label = 'Completed';
            $class = "text-white border-emerald-500 bg-emerald-500/75";
            break;
        default:
            # code...
            break;
    }
@endphp

<strong class="px-3 py-1 text-xs font-semibold border rounded-full {{ $class }}">
    {{ $label }}
</strong>