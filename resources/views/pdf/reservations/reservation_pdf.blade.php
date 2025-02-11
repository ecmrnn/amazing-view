<x-pdf-layout>
    <x-slot:title>{{ $reservation->rid . ' - Reservation Summary' }}</x-slot:title>
    
    <div class="space-y-5">
        <header class="flex items-start justify-between">
            <div class="flex items-center gap-5">
                <x-application-logo width="w-14" />
    
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
                    <p class="font-semibold">{{ $reservation->resched_date_in == null ? date_format(date_create($reservation->date_in), 'F j, Y') : date_format(date_create($reservation->resched_date_in), 'F j, Y');  }}</p>
                    <p class="text-xs">Check-in Date</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->resched_date_out == null ? date_format(date_create($reservation->date_out), 'F j, Y') : date_format(date_create($reservation->resched_date_out), 'F j, Y');  }}</p>
                    <p class="text-xs">Check-out Date</p>
                </div>
            </div>
            <div class="grid grid-cols-2 p-5 rounded-md bg-slate-50">
                <div>
                    <p class="font-semibold">{{ $reservation->adult_count }}</p>
                    <p class="text-xs">Number of Adults</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->children_count }}</p>
                    <p class="text-xs">Number of Children</p>
                </div>
                @if ($reservation->pwd_count > 0)
                    <div>
                        <p class="font-semibold">{{ $reservation->pwd_count }}</p>
                        <p class="text-xs">Number of PWDs</p>
                    </div>
                @endif
                @if ($reservation->senior_count > 0)
                    <div>
                        <p class="font-semibold">{{ $reservation->senior_count }}</p>
                        <p class="text-xs">Number of Seniors</p>
                    </div>
                @endif
            </div>
        </div>

        <h2 class="font-semibold">Guest Details</h2>

        <div>
            <div class="grid grid-cols-2 gap-5 p-5 rounded-md bg-slate-50">
                <div>
                    <p class="font-semibold capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</p>
                    <p class="text-xs">Name</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->address }}</p>
                    <p class="text-xs">Address</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->email }}</p>
                    <p class="text-xs">Email</p>
                </div>
                <div>
                    <p class="font-semibold">{{ $reservation->phone }}</p>
                    <p class="text-xs">Contact Number</p>
                </div>
            </div>
        </div>

        <h2 class="font-semibold">Reservation Breakdown</h2>
        
        <livewire:app.reservation-breakdown :reservation="$reservation" />
    </div>
</x-pdf-layout>