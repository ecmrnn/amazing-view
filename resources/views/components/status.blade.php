@props([
    'status' => '',
    'type' => '',
])

@php
    $statusLabels = [
        'reservation' => [
            0 => ['Confirmed', 'text-green-800 border-green-500 bg-green-50'],
            1 => ['Pending', 'text-orange-800 border-orange-500 bg-orange-50'],
            2 => ['Expired', 'text-red-800 border-red-500 bg-red-50'],
            3 => ['Checked-in', 'text-blue-800 border-blue-500 bg-blue-50'],
            4 => ['Checked-out', 'text-gray-800 bg-gray-200 border-gray-500'],
            5 => ['Completed', 'text-lime-800 border-lime-500 bg-lime-50'],
            6 => ['Canceled', 'text-stone-800 border-stone-500 bg-stone-200'],
            7 => ['Reserved', 'text-green-800 border-green-500 bg-green-50'],
            8 => ['Awaiting Payment', 'text-yellow-800 border-yellow-500 bg-yellow-50'],
            9 => ['No Show', 'text-stone-800 border-stone-500 bg-stone-200'],
            10 => ['Rescheduled', 'text-indigo-800 border-indigo-500 bg-indigo-50'],
        ],
        'room' => [
            0 => ['Available', 'text-blue-800 border-blue-500 bg-blue-50'],
            1 => ['Unavailable', 'text-red-800 border-red-500 bg-red-50'],
            2 => ['Occupied', 'text-orange-800 border-orange-500 bg-orange-50'],
            3 => ['Reserved', 'text-green-800 border-green-500 bg-green-50'],
        ],
        'invoice' => [
            0 => ['Partial', 'text-yellow-800 border-yellow-500 bg-yellow-50'],
            1 => ['Paid', 'text-blue-800 border-blue-500 bg-blue-50'],
            2 => ['Pending', 'text-blue-800 border-blue-500 bg-blue-50'],
            3 => ['Due', 'text-red-800 border-red-500 bg-red-50'],
            4 => ['Canceled', 'text-stone-800 border-stone-500 bg-stone-200'],
            5 => ['Issued', 'text-green-800 border-green-500 bg-green-50'],
            6 => ['Completed', 'text-green-800 border-green-500 bg-green-50'],
        ],
        'page' => [
            0 => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            1 => ['Disabled', 'text-red-800 border-red-500 bg-red-50'],
            2 => ['Maintenance', 'text-amber-800 border-amber-500 bg-amber-50'],
        ],
        'featured_service' => [
            0 => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            1 => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'testimonial' => [
            0 => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            1 => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'milestone' => [
            0 => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            1 => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'user' => [
            1 => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            2 => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'session' => [
            0 => ['Online', 'text-green-800 border-green-500 bg-green-50'],
            1 => ['Offline', 'text-stone-800 border-stone-500 bg-stone-200'],
        ],
    ];

    // Default values if status/type is not found
    $default = ['Unknown', 'text-gray-800 border-gray-500 bg-gray-200'];

    // Get label and class, fallback to default
    [$label, $class] = $statusLabels[$type][$status] ?? $default;
@endphp

<strong class="px-3 py-1 text-xs font-semibold border rounded-full {{ $class }}">
    {{ $label }}
</strong>