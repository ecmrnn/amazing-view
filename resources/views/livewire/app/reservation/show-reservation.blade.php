<div class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
    <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-3 sm:gap-5">
            @php
                if (Auth::user()->role == App\Enums\UserRole::GUEST->value) {
                    $route = route('app.reservations.guest-reservations', ['user' => Auth::user()->id]);
                } else {
                    $route = route('app.reservations.index');
                }
            @endphp
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ $route }}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
            
            <div>
                <h2 class="text-lg font-semibold">Reservation Details</h2>
                <p class="max-w-sm text-xs">Confirm and view reservation</p>
            </div>
        </div>

        {{-- Action buttons --}}
        <x-actions>
            <div class="space-y-1">
                @can('update reservation')
                    @if (in_array($reservation->status, [
                            App\Enums\ReservationStatus::AWAITING_PAYMENT->value,
                            App\Enums\ReservationStatus::PENDING->value,
                            App\Enums\ReservationStatus::CONFIRMED->value,
                            App\Enums\ReservationStatus::CHECKED_IN->value,
                        ]))
                        <a href="{{ route('app.reservations.edit', ['reservation' => $reservation->rid]) }}" wire:navigate>
                            <x-action-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                                <p>Edit</p>
                            </x-action-button>
                        </a>
                    @endif
                @endcan

                @php
                    if (Auth::user()->role == App\Enums\UserRole::GUEST->value) {
                        $billing_route = route('app.billings.show-guest-billings', ['billing' => $reservation->invoice->iid]);
                    } else {
                        $billing_route = route('app.billings.show' , ['billing' => $reservation->invoice->iid]);
                    }
                @endphp

                <a href="{{ $billing_route }}" wire:navigate>
                    <x-action-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                        <p>View Bill</p>
                    </x-action-button>
                </a>

                @if ($reservation->status == App\Enums\ReservationStatus::AWAITING_PAYMENT->value)
                    <x-action-button x-on:click="$dispatch('open-modal', 'show-payment-reservation'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                        <p>Add Payment</p>
                    </x-action-button>
                @endif

                @can('update reservation')
                    @if ($reservation->status == App\Enums\ReservationStatus::PENDING->value)
                        <x-action-button x-on:click="$dispatch('open-modal', 'show-downpayment-modal'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                            <p>Confirm</p>
                        </x-action-button>
                    @endif
                @endcan
                
                <button type="button" wire:click='downloadPdf' class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    <p>Download PDF</p>
                </button>
                
                @hasanyrole(['admin', 'receptionist'])
                    <x-action-button x-on:click="$dispatch('open-modal', 'show-send-email-modal'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <p>Send Email</p>
                    </x-action-button>

                    @if ($reservation->status == App\Enums\ReservationStatus::CONFIRMED->value && $reservation->date_in == date_format(date_create(), 'Y-m-d'))
                        <x-action-button x-on:click="$dispatch('open-modal', 'show-check-in-modal'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                            <p>Check-in</p>
                        </x-action-button>
                    @endif
                    @if ($reservation->status == App\Enums\ReservationStatus::CHECKED_IN->value)
                        <a href="{{ route('app.reservation.check-out', ['reservation' => $reservation->rid]) }}" wire:navigate>
                            <x-action-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                <p>Check-out</p>
                            </x-action-button>
                        </a>
                    @endif
    
                    @can('cancel reservation')
                        @if (in_array($reservation->status, [
                                App\Enums\ReservationStatus::PENDING->value,
                                App\Enums\ReservationStatus::CONFIRMED->value,
                                App\Enums\ReservationStatus::AWAITING_PAYMENT->value
                            ]))
                        
                            <x-action-button x-on:click="$dispatch('open-modal', 'show-cancel-reservation'); dropdown = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                                <p>Cancel</p>
                            </x-action-button>
                        @endif
                    @endcan
    
                    @can('reactivate reservation')
                        @if ($reservation->status == \App\Enums\ReservationStatus::EXPIRED->value)
                            <x-action-button x-on:click="$dispatch('open-modal', 'show-reactivate-modal'); dropdown = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                <p>Reactivate</p>
                            </x-action-button>
                        @endif
                    @endcan
    
                    @can('delete reservation')
                        <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold text-red-500 rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-delete-reservation-modal'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            <p>Delete</p>
                        </button>
                    @endcan
                @endhasanyrole
            </div>
        </x-actions>
    </div>

    {{-- Exipred Reservation --}}
    @if ($reservation->status == \App\Enums\ReservationStatus::EXPIRED->value)
        <x-danger-message>
            <div>
                <h2 class="font-semibold">This reservation has expired!</h2>
                <p class="text-xs">Expiration date:
                    {{ date_format(date_create($reservation->expires_at), 'F j, Y \a\t h:i A') }}</p>
            </div>
        </x-danger-message>
    @endif

    {{-- Exipred Reservation --}}
    @if ($reservation->status == \App\Enums\ReservationStatus::NO_SHOW->value)
        <x-danger-message>
            <div>
                <h2 class="font-semibold">Missed Reservation!</h2>
                <p class="text-xs">Check-in Date: {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
            </div>
        </x-danger-message>
    @endif

    {{-- Awaiting Payment Reservations --}}
    @if ($reservation->status == \App\Enums\ReservationStatus::AWAITING_PAYMENT->value)
        <x-warning-message>
            <div>
                <h2 class="font-semibold">This reservation is awaiting payment!</h2>
                <p class="text-xs">Expiration date: {{ date_format(date_create($reservation->expires_at), 'F j, Y \a\t h:i A') }}</p>
            </div>
        </x-warning-message>
    @endif

    {{-- Rescheduled Reservation --}}
    @if (!empty($reservation->rescheduledFrom) || !empty($reservation->rescheduledTo) && in_array($reservation->status, [
            \App\Enums\ReservationStatus::AWAITING_PAYMENT->value,
            \App\Enums\ReservationStatus::PENDING->value,
            \App\Enums\ReservationStatus::CONFIRMED->value,
            \App\Enums\ReservationStatus::RESCHEDULED->value,
        ]))
        <x-info-message>
            <div>
                @if (!empty($reservation->rescheduledFrom))
                    <p class="text-xs">Rescheduled From: <a wire:navigate class="font-semibold" href="{{ route('app.reservations.show', ['reservation' => $reservation->rescheduledFrom->rid]) }}">{{ $reservation->rescheduledFrom->rid }}</a></p>
                @endif
                @if (!empty($reservation->rescheduledTo))
                    <p class="text-xs">Rescheduled To: <a wire:navigate class="font-semibold" href="{{ route('app.reservations.show', ['reservation' => $reservation->rescheduledTo->rid]) }}">{{ $reservation->rescheduledTo->rid }}</a></p>
                @endif
            </div>
        </x-info-message>
    @endif

    {{-- Late Check-out --}}
    @if ($is_late_checkout)
        <x-danger-message>
            <div class="flex flex-col items-start justify-between gap-5 md:flex-row">
                <hgroup>
                    <h2 class='font-semibold text-red-500'>This reservation is late for check-out!</h2>
                    <p class='text-xs'>This guest checkout date is {{ date_format(date_create($reservation->date_out), 'F j, Y') . ' at ' . date_format(date_create($reservation->time_out), 'h:i A') }}.</p>
                </hgroup>

                <a href="{{ route('app.reservation.check-out', ['reservation' => $reservation->rid]) }}" wire:navigate>
                    <x-danger-button type="button">
                        Check-out
                    </x-danger-button>
                </a>
            </div>
        </x-danger-message>
    @endif

    <section x-data="{ show: false }" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <div class="grid font-bold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-md aspect-square w-full max-w-[50px] place-items-center">
                <p class="text-xl">{{ ucwords($reservation->user->first_name[0]) . ucwords($reservation->user->last_name[0]) }}</p>
            </div>

            <hgroup>
                <h2 class="overflow-hidden text-lg font-semibold capitalize text-ellipsis whitespace-nowrap">
                    {{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</h2>
                <p class="text-xs">Full Name</p>
            </hgroup>
        </div>

        <div class="grid grid-cols-1 gap-3 p-5 border rounded-md border-slate-200 sm:grid-cols-2">
            {{-- Name --}}
            <div class="flex items-center gap-3">
                <x-icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-at-sign-icon lucide-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                </x-icon>
                <div>
                    <p class="text-sm font-semibold">{{ $reservation->user->email }}</p>
                    <p class="text-xs">Email Address</p>
                </div>
            </div>
            {{-- Phone --}}
            <div class="flex items-center gap-3">
                <x-icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                </x-icon>
                <div>
                    <p class="text-sm font-semibold">{{ substr($reservation->user->phone, 0, 4) . ' ' . substr($reservation->user->phone, 4, 3) . ' ' . substr($reservation->user->phone, 7) }}</p>
                    <p class="text-xs">Phone</p>
                </div>
            </div>
            {{-- Address --}}
            @if ($reservation->user->address)
                <div class="flex items-center gap-3 sm:col-span-2">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold line-clamp-1 hover:line-clamp-none">{{ $reservation->user->address }}</p>
                        <p class="text-xs">Address</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center justify-between gap-5">
            <hgroup>
                <h2 class="font-semibold">{{ $reservation->rid }}</h2>
                <p class="text-xs">Reservation ID</p>
            </hgroup>

            <x-status type="reservation" :status="$reservation->status" />
        </div>

        {{-- Reservation Details --}}
        <div class="grid grid-cols-1 gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
            {{-- Check-in date --}}
            <div class="flex items-center gap-3">
                <x-icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up-icon lucide-calendar-arrow-up"><path d="m14 18 4-4 4 4"/><path d="M16 2v4"/><path d="M18 22v-8"/><path d="M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                </x-icon>
                <div>
                    <p class="text-sm font-semibold">{{ date_format(date_create($reservation->date_in), 'F j, Y') . ' at ' . date_format(date_create($reservation->time_in), 'g:i A') }}</p>
                    <p class="text-xs">Check-in date and time</p>
                </div>
            </div>
            {{-- Check-in date --}}
            <div class="flex items-center gap-3">
                <x-icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down-icon lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                </x-icon>
                <div>
                    <p class="text-sm font-semibold">{{ date_format(date_create($reservation->date_out), 'F j, Y') . ' at ' . date_format(date_create($reservation->time_out), 'g:i A') }}</p>
                    <p class="text-xs">Check-in date and time</p>
                </div>
            </div>
            {{-- Total number of guests --}}
            <div class="flex items-center gap-3">
                <x-icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </x-icon>
                <div>
                    <p class="text-sm font-semibold">
                        {{ $reservation->adult_count > 1 ? $reservation->adult_count . ' Adults' : $reservation->adult_count . ' Adult' }}
                        @if ($reservation->children_count > 0)
                            {{ ' & ' }}
                            {{ $reservation->children_count > 1 ?  $reservation->children_count . ' Children' : $reservation->children_count . ' Child' }}
                        @endif    
                    </p>
                    <p class="text-xs">Total number of guests</p>
                </div>
            </div>
            @if ($reservation->senior_count > 0 || $reservation->pwd_count > 0)
                {{-- Total number of seniors and pwds --}}
                <div class="flex items-center gap-3">
                    <x-icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility-icon lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>
                    </x-icon>
                    <div>
                        <p class="text-sm font-semibold">
                            @if ($reservation->senior_count > 0)
                                {{ $reservation->senior_count > 1 ?  $reservation->senior_count . ' Seniors' : $reservation->senior_count . ' Senior' }}
                            @endif
                            @if ($reservation->pwd_count > 0)
                                @if ($reservation->senior_count > 0)
                                    {{ ' & ' }}
                                @endif
                                {{ $reservation->pwd_count > 1 ?  $reservation->pwd_count . ' PWDs' : $reservation->pwd_count . ' PWD' }}
                            @endif   
                        </p>
                        <p class="text-xs">Total number of guests</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Rooms --}}
        <div class="space-y-5">
            <p class="font-semibold">Rooms</p>

            <div class="grid gap-5 sm:grid-cols-2">
                @forelse ($reservation->rooms as $room)
                    <div class="grid grid-cols-2 gap-5 p-5 border rounded-md border-slate-200">
                        <div>
                            <p class="font-semibold">{{ $room->room_number }}</p>
                            <p class="text-xs">Room Number</p>
                        </div>

                        <div>
                            <p class="font-semibold"><x-currency />{{ number_format($room->pivot->rate, 2) }}</p>
                            <p class="text-xs">Room Rate</p>
                        </div>
                    </div>
                @empty
                    <div class="py-5 text-sm font-semibold text-center border rounded-lg sm:col-span-2">
                        No rooms yet...
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <div class="font-semibold">Reservation Breakdown</div>
            <p class="text-xs">Review reservation details here</p>
        </hgroup>

        <livewire:app.reservation-breakdown :reservation="$reservation" />
    </section>

    <livewire:app.reservation.confirm-reservation :reservation="$reservation" />
</div>
