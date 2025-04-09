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
        <a href="{{ route('app.reservation.check-out', ['reservation' => $row->rid]) }}" wire:navigate>
            <x-tooltip text="Check-out" dir="top">
                <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'show-checkout-guest-{{ $row->id}}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                </x-icon-button>
            </x-tooltip>
        </a>
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
    
    <x-modal.full name='show-checkin-guest-{{ $row->id}}' maxWidth='sm'>
        <div class="p-5 space-y-5" x-on:guest-checked-in.window="show = false">
            <hgroup>
                <h2 class="font-semibold capitalize">Check-in Guest</h2>
                <p class="max-w-sm text-sm">Confirm the guest&apos;s details here</p>
            </hgroup>

            <div class="flex items-center w-full gap-3 px-3 py-2 text-xs border rounded-md border-emerald-500 bg-emerald-50">
                <svg class="self-start flex-shrink-0 text-emerald-800" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                <p class="text-emerald-800">Ready for check-in!</p>
            </div>

            <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200 ">
                <hgroup class="flex items-center justify-between">
                    <h3 class="text-base font-semibold">{{ $row->rid }}</h3>

                    <x-status type="reservation" :status="$row->status" />
                </hgroup>
            </div>

            <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                <div>
                    <p class="text-base font-semibold capitalize">{{ $row->user->first_name . ' ' . $row->user->last_name }}</p>
                    <p class="flex justify-between text-sm capitalize">Name</p>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <p class="text-base font-semibold capitalize">{{ date_format(date_create($row->date_in), 'F j, Y') }}</p>
                        <p class="flex justify-between text-sm capitalize">Check-in Date</p>
                    </div>
                    <div>
                        <p class="text-base font-semibold capitalize">{{ date_format(date_create($row->date_out), 'F j, Y') }}</p>
                        <p class="flex justify-between text-sm capitalize">Check-out Date</p>
                    </div>
                </div>
            </div>
            
            <x-loading wire:loading wire:target="checkIn">Checking-in the guest</x-loading>
            
            <div class="flex justify-between gap-1">
                <x-secondary-button type="button" x-on:click="show = false; $wire.set('reservation', null)">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click="checkIn({{ $row->id }})" wire:loading.attr='disabled'>Check-in</x-primary-button>
            </div>
        </div>
    </x-modal.full>
</div>
