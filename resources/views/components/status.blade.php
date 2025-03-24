@props([
    'status' => '',
    'type' => '',
])

@php
    $statusLabels = [
        'reservation' => [
            \App\Enums\ReservationStatus::CONFIRMED->value => ['Confirmed', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\ReservationStatus::PENDING->value => ['Pending', 'text-orange-800 border-orange-500 bg-orange-50'],
            \App\Enums\ReservationStatus::EXPIRED->value => ['Expired', 'text-red-800 border-red-500 bg-red-50'],
            \App\Enums\ReservationStatus::CHECKED_IN->value => ['Checked-in', 'text-blue-800 border-blue-500 bg-blue-50'],
            \App\Enums\ReservationStatus::CHECKED_OUT->value => ['Checked-out', 'text-gray-800 bg-gray-200 border-gray-500'],
            \App\Enums\ReservationStatus::COMPLETED->value => ['Completed', 'text-lime-800 border-lime-500 bg-lime-50'],
            \App\Enums\ReservationStatus::CANCELED->value => ['Canceled', 'text-stone-800 border-stone-500 bg-stone-200'],
            \App\Enums\ReservationStatus::RESERVED->value => ['Reserved', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\ReservationStatus::AWAITING_PAYMENT->value => ['Awaiting Payment', 'text-yellow-800 border-yellow-500 bg-yellow-50'],
            \App\Enums\ReservationStatus::NO_SHOW->value => ['No Show', 'text-stone-800 border-stone-500 bg-stone-200'],
            \App\Enums\ReservationStatus::RESCHEDULED->value => ['Rescheduled', 'text-indigo-800 border-indigo-500 bg-indigo-50'],
        ],
        'room' => [
            \App\Enums\RoomStatus::AVAILABLE->value => ['Available', 'text-blue-800 border-blue-500 bg-blue-50'],
            \App\Enums\RoomStatus::UNAVAILABLE->value => ['Unavailable', 'text-red-800 border-red-500 bg-red-50'],
            \App\Enums\RoomStatus::OCCUPIED->value => ['Occupied', 'text-orange-800 border-orange-500 bg-orange-50'],
            \App\Enums\RoomStatus::RESERVED->value => ['Reserved', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\RoomStatus::DISABLED->value => ['Disabled', 'text-stone-800 border-stone-500 bg-stone-200'],
        ],
        'invoice' => [
            \App\Enums\InvoiceStatus::PARTIAL->value => ['Partial', 'text-yellow-800 border-yellow-500 bg-yellow-50'],
            \App\Enums\InvoiceStatus::PAID->value => ['Paid', 'text-blue-800 border-blue-500 bg-blue-50'],
            \App\Enums\InvoiceStatus::PENDING->value => ['Pending', 'text-orange-800 border-orange-500 bg-orange-50'],
            \App\Enums\InvoiceStatus::DUE->value => ['Due', 'text-red-800 border-red-500 bg-red-50'],
            \App\Enums\InvoiceStatus::CANCELED->value => ['Canceled', 'text-stone-800 border-stone-500 bg-stone-200'],
            \App\Enums\InvoiceStatus::ISSUED->value => ['Issued', 'text-green-800 border-green-500 bg-green-50'],
        ],
        'page' => [
            \App\Enums\PageStatus::ACTIVE->value => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\PageStatus::DISABLED->value => ['Disabled', 'text-red-800 border-red-500 bg-red-50'],
            \App\Enums\PageStatus::MAINTENANCE->value => ['Maintenance', 'text-amber-800 border-amber-500 bg-amber-50'],
        ],
        'featured_service' => [
            \App\Enums\FeaturedServiceStatus::ACTIVE->value => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\FeaturedServiceStatus::INACTIVE->value => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'testimonial' => [
            \App\Enums\TestimonialStatus::ACTIVE->value => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\TestimonialStatus::INACTIVE->value => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'milestone' => [
            \App\Enums\MilestoneStatus::ACTIVE->value => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\MilestoneStatus::INACTIVE->value => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'user' => [
            \App\Enums\UserStatus::ACTIVE->value => ['Active', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\UserStatus::INACTIVE->value => ['Inactive', 'text-red-800 border-red-500 bg-red-50'],
        ],
        'session' => [
            \App\Enums\SessionStatus::ONLINE->value => ['Online', 'text-green-800 border-green-500 bg-green-50'],
            \App\Enums\SessionStatus::OFFLINE->value => ['Offline', 'text-stone-800 border-stone-500 bg-stone-200'],
        ],
    ];

    // Default values if status/type is not found
    $default = ['Unknown', 'text-gray-800 border-gray-500 bg-gray-200'];

    // Get label and class, fallback to default
    [$label, $class] = $statusLabels[$type][$status] ?? $default;
@endphp

<strong class="px-3 py-1 text-xs w-max inline-block font-semibold border rounded-full {{ $class }}">
    {{ $label }}
</strong>