@props([
    'status' => '',
    'type' => '', /* reservation | room | invoice | page */
])

@php
    $label = '';
    $class = '';
    switch ($status) {
        case 0:
            switch ($type) {
                // Green
                case 'reservation':
                    $label = 'Confirmed';
                    $class = 'text-green-800 border-green-500 bg-green-50';
                    break;
                case 'room':
                    $label = 'Available';
                    $class = 'text-blue-800 border-blue-500 bg-blue-50';
                    break;
                case 'invoice':
                    $label = 'Partial';
                    $class = 'text-yellow-800 border-yellow-500 bg-yellow-50';
                    break;
                case 'page':
                    $label = 'Active';
                    $class = 'text-green-800 border-green-500 bg-green-50';
                    break;
            }
            break;
        case 1:
            switch ($type) {
                case 'reservation':
                    $label = 'Pending';
                    $class = 'text-orange-800 border-orange-500 bg-orange-50';
                    break;
                case 'room':
                    $label = 'Unavailable';
                    $class = 'text-red-800 border-red-500 bg-red-50';
                    break;
                case 'invoice':
                    $label = 'Paid';
                    $class = 'text-blue-800 border-blue-500 bg-blue-50';
                    break;
            }
            break;
        case 2:
            switch ($type) {
                case 'reservation':
                    $label = 'Expired';
                    $class = 'text-red-800 border-red-500 bg-red-50';
                    break;
                case 'room':
                    $label = 'Occupied';
                    $class = 'text-orange-800 border-orange-500 bg-orange-50';
                    break;
                case 'invoice':
                    $label = 'Pending';
                    $class = 'text-blue-800 border-blue-500 bg-blue-50';
                    break;
            }
            break;
        case 3:
            switch ($type) {
                case 'reservation':
                    $label = 'Checked-in';
                    $class = 'text-blue-800 border-blue-500 bg-blue-50';
                    break;
                case 'room':
                    $label = 'Reserved';
                    $class = 'text-green-800 border-green-500 bg-green-50';
                    break;
                case 'invoice':
                    $label = 'Due';
                    $class = 'text-red-800 border-red-500 bg-red-50';
                    break;
            }
            break;
        case 4:
            switch ($type) {
                case 'reservation':
                    $label = 'Checked-out';
                    $class = 'text-gray-800 bg-gray-200 border-gray-500';
                    break;
                case 'invoice':
                    $label = 'Canceled';
                    $class = 'text-stone-800 border-stone-500 bg-stone-200';
                    break;
            }
            break;
        case 5:
            switch ($type) {
                case 'reservation':
                    $label = 'Completed';
                    $class = 'text-lime-800 border-lime-500 bg-lime-50';
                    break;
                case 'invoice':
                    $label = 'Issued';
                    $class = 'text-green-800 border-green-500 bg-green-50';
                    break;
            }
            break;
        case 6:
            switch ($type) {
                case 'reservation':
                    $label = 'Canceled';
                    $class = 'text-stone-800 border-stone-500 bg-stone-200';
                    break;
                case 'invoice':
                    $label = 'Completed';
                    $class = 'text-green-800 border-green-500 bg-green-50';
                    break;
            }
            break;
        case 7:
            $label = 'Reserved';
            $class = 'text-green-800 border-green-500 bg-green-50';
            break;
        case 8:
            $label = 'Awaiting Payment';
            $class = 'text-yellow-800 border-yellow-500 bg-yellow-50';
            break;
        case 9:
            $label = 'No Show';
            $class = 'text-stone-800 border-stone-500 bg-stone-200';
            break;
        case 10:
            $label = 'Rescheduled';
            $class = 'text-indigo-800 border-indigo-500 bg-indigo-50';
            break;
        default:
            # code...
            break;
    }
@endphp

<strong class="px-3 py-1 text-xs font-semibold border rounded-full {{ $class }}">
    {{ $label }}
</strong>
