@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
    'view_link' => '',
])

<div class="flex justify-end gap-1">
    @if (in_array($row->status, [
        App\Enums\ReservationStatus::AWAITING_PAYMENT->value,
        App\Enums\ReservationStatus::PENDING->value,
        App\Enums\ReservationStatus::CONFIRMED->value,
    ]))
        <x-tooltip text="Edit" dir="top">
            <a x-ref="content" href="{{ route($edit_link, ['reservation' => $row->rid]) }}" wire:navigate.hover>
                <x-icon-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-pencil">
                        <path
                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                        <path d="m15 5 4 4" />
                    </svg>
                </x-icon-button>
            </a>
        </x-tooltip>
    @endif

    @can('delete reservation')
        <x-tooltip text="Delete" dir="top">
            <a x-ref="content">
                <x-icon-button x-on:click="$dispatch('open-modal', 'delete-reservation-{{ $row->id }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                </x-icon-button>
            </a>
        </x-tooltip>
    @endcan

    <x-tooltip text="View" dir="top">
        <a x-ref="content" href="{{ route($view_link, ['reservation' => $row->rid]) }}" wire:navigate.hover>
            <x-icon-button>
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" /><circle cx="12" cy="12" r="3" /></svg>
            </x-icon-button>
        </a>
    </x-tooltip>

    <x-modal.full name='delete-reservation-{{ $row->id }}' maxWidth='sm'>
        <form class="p-5 space-y-5 bg-white" x-on:submit.prevent="$dispatch('delete-reservation', { id: {{ $row->id  }}})">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500 capitalize">Delete Reservation</h2>
                <p class="text-xs">You are about to delete this reservation, this action cannot be undone</p>
            </hgroup>
    
            <div class="space-y-2">
                <x-form.input-label for="password-{{ $row->id }}">Enter your password to delete this reservation</x-form.input-label>
                <x-form.input-text wire:model="password" type="password" label="Password" id="password-{{ $row->id }}" />
                <x-form.input-error field="password" />
            </div>
            
            <div class="flex items-center justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="submit">Delete</x-danger-button>
            </div>
        </form>
    </x-modal.full>
</div>
