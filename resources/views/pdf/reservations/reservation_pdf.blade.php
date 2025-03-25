<x-pdf-layout>
    <x-slot:title>{{ $reservation->rid . ' - Reservation Summary' }}</x-slot:title>
    
    <div class="space-y-5">
        <header class="flex items-start justify-between">
            <div class="flex items-center gap-5">
                <x-img src="{{ $settings['site_logo'] ?? '' }}" aspect="square" class="w-12" />
    
                <div>
                    <h1 class="text-base font-semibold">Amazing View Mountain Resort</h1>
                    <p class="text-xs">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
                </div>
            </div>
    
            <div>
                <p class="text-base font-semibold text-right">{{ $reservation->rid }}</p>
                <p class="text-xs text-right">Reservation ID</p>
            </div>
        </header>

        <h2 class="font-semibold">Reservation Details</h2>

        <div class="grid grid-cols-2 gap-5">
            <div class="grid grid-cols-2 p-5 rounded-md bg-slate-50">
                <div>
                    <p class="font-semibold">{{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                    <p class="text-xs">Check-in Date</p>
                </div>
                <div>
                    <p class="font-semibold">{{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                    <p class="text-xs">Check-out Date</p>
                </div>
            </div>
            <div class="grid gap-5 p-5 border rounded-md sm:grid-cols-2 border-slate-200">
                <div>
                    <p class="font-semibold">
                        {{ $reservation->adult_count > 1 ? $reservation->adult_count . ' Adults' : $reservation->adult_count . ' Adult' }}
                        @if ($reservation->children_count > 0)
                            {{ ' & ' }}
                            {{ $reservation->children_count > 1 ?  $reservation->children_count . ' Children' : $reservation->children_count . ' Child' }}
                        @endif
                    </p>
                    <p class="text-xs">Total Number of Guests</p>
                </div>
                @if ($reservation->senior_count > 0 || $reservation->pwd_count > 0)
                    <div>
                        <p class="font-semibold">
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
                        <p class="text-xs">Seniors and PWDs</p>
                    </div>
                @endif
            </div>
        </div>

        <h2 class="font-semibold">Guest Details</h2>

        <div>
            <div class="grid grid-cols-2 gap-5 p-5 rounded-md bg-slate-50">
                <div>
                    <p class="font-semibold capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</p>
                    <p class="text-xs">Name</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->user->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->user->email }}</p>
                    <p class="text-xs">Email</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->user->phone }}</p>
                    <p class="text-xs">Contact Number</p>
                </div>
            </div>
        </div>

        <h2 class="font-semibold">Reservation Breakdown</h2>
        
        <livewire:app.reservation-breakdown :reservation="$reservation" />
    </div>
</x-pdf-layout>