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

            <div class="flex items-center w-full gap-3 px-3 py-2 text-xs text-green-800 border border-green-500 rounded-md bg-green-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                <p>Ready for check-in!</p>
            </div>

            <div class="p-5 space-y-3 bg-white border rounded-md border-slate-200">
                {{-- Reservation ID --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code-icon lucide-qr-code"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ $row->rid }}</p>
                        <p class="text-xs">Reservation ID</p>
                    </div>
                </div>
                {{-- Name --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ $row->user->name() }}</p>
                        <p class="text-xs">Name</p>
                    </div>
                </div>
            </div>

            <div class="p-5 space-y-3 bg-white border rounded-md border-slate-200">
                {{-- Check-in date --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 18 4-4 4 4"/><path d="M16 2v4"/><path d="M18 22v-8"/><path d="M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ date_format(date_create($row->date_in), 'F j, Y') . ' at ' . date_format(date_create($row->time_in), 'g:i A') }}</p>
                        <p class="text-xs">Check-in date and time</p>
                    </div>
                </div>
                {{-- Check-in date --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down-icon lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">{{ date_format(date_create($row->date_out), 'F j, Y') . ' at ' . date_format(date_create($row->time_out), 'g:i A') }}</p>
                        <p class="text-xs">Check-out date and time</p>
                    </div>
                </div>
            </div>
            
            <x-loading wire:loading wire:target="checkIn">Checking-in the guest</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false;">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click="checkIn({{ $row->id }})" wire:loading.attr='disabled'>Check-in</x-primary-button>
            </div>
        </div>
    </x-modal.full>
</div>
