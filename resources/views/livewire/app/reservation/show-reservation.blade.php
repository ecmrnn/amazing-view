<div class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
    <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-3 sm:gap-5">
            <x-back />
            
            <div>
                <h2 class="text-lg font-semibold">Reservation Details</h2>
                <p class="max-w-sm text-xs">Confirm and view reservation</p>
            </div>
        </div>

        {{-- Action buttons --}}
        <x-actions>
            <div class="space-y-1">
                <a href="{{ route('app.reservations.edit', ['reservation' => $reservation->rid]) }}" wire:navigate>
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                        <p>Edit</p>
                    </button>
                </a>

                @if ($reservation->status == App\Enums\ReservationStatus::AWAITING_PAYMENT->value)
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-payment-reservation'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                        <p>Add Payment</p>
                    </button>
                @endif

                @if ($reservation->status == App\Enums\ReservationStatus::PENDING->value)
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-downpayment-modal'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                        <p>Confirm</p>
                    </button>
                @endif
                
                <button type="button" wire:click='downloadPdf' class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    <p>Download PDF</p>
                </button>
    
                <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <p>Send Email</p>
                </button>
            </div>

            <div class="w-full h-px bg-slate-200"></div>

            <div class="space-y-1">
                @if ($reservation->status == App\Enums\ReservationStatus::CHECKED_IN->value)
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-checkout-reservation'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        <p>Check-out</p>
                    </button>
                @endif

                @if ($reservation->status == App\Enums\ReservationStatus::PENDING->value || $reservation->status == App\Enums\ReservationStatus::CONFIRMED->value || $reservation->status == App\Enums\ReservationStatus::AWAITING_PAYMENT->value)
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-cancel-reservation'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                        <p>Cancel</p>
                    </button>
                @endif

                @if ($reservation->status == \App\Enums\ReservationStatus::EXPIRED->value)
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-reactivate-modal'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        <p>Reactivate</p>
                    </button>
                @endif

                @can('delete reservation')
                    <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold text-red-500 rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-delete-reservation-modal'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <p>Delete</p>
                    </button>
                @endcan
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
                <p class="text-xs">Check-in Date: {{ $reservation->resched_date_in == null ? date_format(date_create($reservation->date_in), 'F j, Y') : date_format(date_create($reservation->resched_date_in), 'F j, Y') }}</p>
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

    <section x-data="{ show: false }" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <div class="grid font-bold text-white bg-blue-500 rounded-md aspect-square w-14 place-items-center">
                <p class="text-xl">{{ ucwords($reservation->first_name[0]) . ucwords($reservation->last_name[0]) }}</p>
            </div>

            <hgroup>
                <h2 class="text-lg font-semibold capitalize">
                    {{ $reservation->first_name . ' ' . $reservation->last_name }}</h2>
                <p class="text-xs">Full Name</p>
            </hgroup>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="grid gap-5 p-5 border rounded-md lg:grid-cols-2 border-slate-200">
                <div>
                    <p class="font-semibold">{{ $reservation->email }}</p>
                    <p class="text-xs">Email</p>
                </div>

                <div>
                    <p class="font-semibold">{{ $reservation->phone }}</p>
                    <p class="text-xs">Contact No.</p>
                </div>
                <div class="lg:hidden">
                    <p class="font-semibold">{{ $reservation->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
            </div>

            <div class="hidden p-5 border rounded-md lg:grid lg:grid-cols-2 border-slate-200">
                <div class="lg:col-span-2">
                    <p class="font-semibold">{{ $reservation->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
            </div>
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
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div class="grid gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
                <div>
                    @if (!empty($reservation->resched_date_in))
                        <p class="font-semibold">{{ date_format(date_create($reservation->resched_date_in), 'F j, Y') }}</p>
                    @else
                        <p class="font-semibold">{{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                    @endif
                    <p class="text-xs">Check-in Date</p>
                </div>
                <div>
                    @if (!empty($reservation->resched_date_out))
                        <p class="font-semibold">{{ date_format(date_create($reservation->resched_date_out), 'F j, Y') }}</p>
                    @else
                        <p class="font-semibold">{{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                    @endif
                    <p class="text-xs">Check-out Date</p>
                </div>
            </div>
            <div class="grid gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
                <div>
                    <p class="font-semibold">{{ $reservation->adult_count }}</p>
                    <p class="text-xs">Number of Adults</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->children_count }}</p>
                    <p class="text-xs">Number of Children</p>
                </div>
                @if ($reservation->senior_count > 0)
                    <div>
                        <p class="font-semibold">{{ $reservation->senior_count }}</p>
                        <p class="text-xs">Number of Senior</p>
                    </div>
                @endif
                @if ($reservation->pwd_count > 0)
                    <div>
                        <p class="font-semibold">{{ $reservation->pwd_count }}</p>
                        <p class="text-xs">Number of PWD</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Rooms --}}
        <div class="space-y-5">
            <p class="font-semibold">Rooms</p>

            <div class="grid gap-5 sm:grid-cols-2">
                @forelse ($reservation->rooms as $room)
                    <div class="grid grid-cols-2 gap-5 p-5 border rounded-md border-slate-200">
                        <div>
                            <p class="font-semibold">{{ $room->building->prefix }} {{ $room->room_number }}</p>
                            <p class="text-xs">Room Number</p>
                        </div>

                        <div>
                            <p class="font-semibold"><x-currency />{{ number_format($room->rate, 2) }}</p>
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
</div>
