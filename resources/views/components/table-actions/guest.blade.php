@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
    'view_link' => '',
])

<div class="flex justify-end gap-1" wire:key='{{ $row->id}}'>
    <x-tooltip text="Edit" dir="top">
        <a x-ref="content" href="{{ route($edit_link, ['reservation' => $row->rid]) }}" wire:navigate.hover>
            <x-icon-button>
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /><path d="m15 5 4 4" /></svg>
            </x-icon-button>
        </a>
    </x-tooltip>

    @if ($row->status == \App\Enums\ReservationStatus::CHECKED_IN->value)
        <x-tooltip text="Check-out" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'show-checkout-guest-{{ $row->id}}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            </x-icon-button>
        </x-tooltip>
    @endif
    
    @if ($row->status == \App\Enums\ReservationStatus::CONFIRMED->value)
        <x-tooltip text="Check-in" dir="top">
            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'show-checkin-guest-{{ $row->id}}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
            </x-icon-button>
        </x-tooltip>
    @endif

    <x-tooltip text="View" dir="top">
        <a x-ref="content" href="{{ route($view_link, ['reservation' => $row->rid]) }}" wire:navigate.hover>
            <x-icon-button>
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" /><circle cx="12" cy="12" r="3" /></svg>
            </x-icon-button>
        </a>
    </x-tooltip>
    
    <x-modal.full name='show-checkout-guest-{{ $row->id}}' maxWidth='sm'>
        <div class="p-5 space-y-5" x-on:guest-checked-out.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold capitalize">Check-out Guest</h2>
                <p class="text-sm">Are you sure you really want to check-out this guest?</p>
            </hgroup>

            <div class="p-5 border rounded-md border-slate-200">
                <div>
                    <h3 class="font-semibold">{{ $row->rid }}</h3>
                    <p class="text-xs">Reservation ID</p>
                </div>
            </div>

            <div class="p-5 border rounded-md border-slate-200">
                <div>
                    <h3 class="font-semibold capitalize">{{ $row->first_name . ' ' . $row->last_name}}</h3>
                    <p class="text-xs">Name</p>
                </div>
            </div>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click="checkOut({{ $row->id }})">Check-out</x-primary-button>
            </div>
        </div>
    </x-modal.full>

    <x-modal.full name='show-checkin-guest-{{ $row->id}}' maxWidth='sm'>
        <div wire:key='{{ $row->id }}'>
            
        </div>
    </x-modal.full>
</div>
