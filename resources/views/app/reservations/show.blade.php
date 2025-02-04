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
                <x-secondary-button type="button" x-on:click="dropdown = !dropdown" class="items-center hidden gap-3 text-xs sm:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bolt"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><circle cx="12" cy="12" r="4"/></svg>
                    <div>Actions</div>
                </x-secondary-button>
                
                <x-icon-button type="button" class="sm:hidden" x-on:click="dropdown = !dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bolt"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><circle cx="12" cy="12" r="4"/></svg>
                </x-icon-button>

                {{-- Dropdown --}}
                <div x-show="dropdown" x-on:click.outside="dropdown = !dropdown" class="absolute right-0 p-1 translate-y-1 bg-white border rounded-md w-max top-full border-slate-200">
                    Action buttons goes here
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

            <x-note>Quantity on notes are the total nights the guest will stay.</x-note>
        </section>
    </div>

    @push('modals')
        {{-- Proof of image modal --}}
        <x-modal.full name="show-downpayment-modal" maxWidth="sm">
            <livewire:app.reservation.confirm-reservation :reservation="$reservation" :downpayment="$downpayment" />
        </x-modal.full> 
    @endpush
</x-app-layout>  