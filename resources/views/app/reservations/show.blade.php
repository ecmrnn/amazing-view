<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Reservations') }}
                </h1>
                <p class="text-xs">Manage your reservations here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <div class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
        <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-center gap-3 sm:gap-5">
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ route('app.reservations.index')}}" wire:navigate>
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
            <div x-data="{ dropdown: false }" class="relative">
                <x-secondary-button type="button" x-on:mouseover="dropdown = true" class="items-center hidden gap-3 text-xs sm:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bolt"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><circle cx="12" cy="12" r="4"/></svg>
                    <div>Actions</div>
                </x-secondary-button>
                
                <x-icon-button type="button" class="sm:hidden" x-on:click="dropdown = ! dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bolt"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><circle cx="12" cy="12" r="4"/></svg>
                </x-icon-button>

                {{-- Dropdown --}}
                <div x-show="dropdown" x-on:click.outside="dropdown = !dropdown" class="absolute right-0 p-3 space-y-3 translate-y-1 bg-white border rounded-md shadow-md w-max top-full border-slate-200 min-w-[200px]">
                    <div class="space-y-1">
                        <a href="{{ route('app.reservations.edit', ['reservation' => $reservation->rid]) }}" wire:navigate>
                            <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                                <p>Edit</p>
                            </button>
                        </a>
                        
                        @if ($reservation->status == App\Enums\ReservationStatus::PENDING->value || $reservation->status == App\Enums\ReservationStatus::AWAITING_PAYMENT->value)
                            <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-downpayment-modal'); dropdown = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                                <p>Confirm</p>
                            </button>
                        @endif
                        
                        <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
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
                        <button type="button" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50" x-on:click="$dispatch('open-modal', 'show-cancel-reservation'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                            <p>Cancel</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <section x-data="{ show: false }" class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-center gap-5">
                <div class="grid font-bold text-white bg-blue-500 rounded-md aspect-square w-14 place-items-center">
                    <p class="text-xl">{{ ucwords($reservation->first_name[0]) . ucwords($reservation->last_name[0]) }}</p>
                </div>

                <hgroup>
                    <h2 class="text-lg font-semibold capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</h2>
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

    @push('modals')
        {{-- Proof of image modal --}}
        <x-modal.full name="show-downpayment-modal" maxWidth="sm">
            <livewire:app.reservation.confirm-reservation :reservation="$reservation" :downpayment="$downpayment" />
        </x-modal.full> 

        {{-- Modal for canceling reservation --}}
        <x-modal.full name="show-cancel-reservation" maxWidth="sm">
            <div x-data="{ reason: 'guest' }">
                <livewire:app.reservation.cancel-reservation :reservation="$reservation" />
            </div>
        </x-modal.full> 
    @endpush
</x-app-layout>  