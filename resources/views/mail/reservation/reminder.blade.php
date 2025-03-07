<x-mail-layout>
    <div class="max-w-2xl p-5 mx-auto bg-white md:shadow-lg md:p-10 md:rounded-lg">
        <header class="flex flex-col items-center gap-5 md:flex-row">
            <div class="w-full max-w-24 aspect-square">
                <img src="{{ $message->embed(asset('storage/global/application-logo.png')) }}">
            </div>
        
            <hgroup>
                <p class="text-lg font-bold text-center md:text-left">Amazing View Mountain Resort</p>
                <p class="text-sm text-center md:text-left">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
            </hgroup>
        </header>
    
        <main class="py-10 space-y-5">
            <h1 class="font-bold text-md">Amazing vacation ahead!</h1>

            <p>Hi <span class="capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span>!
                <br></br>
                We are excited to welcome you to Amazing View Mountain Farm Resort soon! This is a friendly reminder about your upcoming stay with us.
            </p>

            <div>
                <p><strong class="font-bold">Check-in Date:</strong> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p><strong class="font-bold">Check-out Date:</strong> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                <div>
                    <strong class="font-bold">Rooms Reserved:</strong> 
                    <div class="mt-5 overflow-hidden border rounded-md border-slate-200">
                        <div class="grid grid-cols-3 px-3 py-2 bg-slate-50">
                            <strong class="block font-bold">Room Number</strong>
                            <strong class="block font-bold">Building</strong>
                            <strong class="block font-bold">Floor</strong>
                        </div>
                        @foreach ($reservation->rooms as $room)
                            <div class="grid grid-cols-3 px-3 py-2">
                                <p>{{ $room->building->prefix . ' ' . $room->room_number }}</p>
                                <p class="capitalize">{{ $room->building->name }}</p>
                                <p>{{ $room->floor_number }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-5 rounded-md bg-slate-50">
                <p class="font-bold">Reminders Upon Arrival</p>
                <p>Please present your Reservation ID on our security personnel for verification.</p>
                
                <ul class="mt-5 list-disc list-inside">
                    <li>Actions that violate our rules and regulations will be fairly compensated.</li>
                    <li>Arrive before or on-time the desired reservation date.</li>
                    <li>Free parking is available for all guests</li>
                </ul>
            </div>

            <div class="p-5 rounded-md bg-slate-50">
                <p class="font-bold">Cancellation Policy</p>
                <p>If you need to cancel your reservation you may reach us through this email or any of our contact details below.</p>
                
                <div class="mt-5">
                    <p><strong class="font-bold">100% Refund</strong> - If cancelled on or before October 19, 2024</p>
                    <p><strong class="font-bold">50% Refund</strong> - If cancelled after October 19, 2024</p>
                    <p><strong class="font-bold">No Refund</strong> - If the guest does not arrive on the scheduled reservation</p>
                </div>
            </div>

            <p>We&apos;ve got everything ready for your arrival. If you have any questions, please don&apos;t hesitate to contact us! Attached below is a copy of your confirmed reservation form.</p>
            <p class="font-bold">We look forward to providing you with an amazing stay! </p>
        </main>
    
        <footer>
            <p class="text-center">💖</p>
            <p class="text-center">Thank you for choosing</p>
            <p class="font-bold text-center text-blue-500">Amazing View Mountain Resort!</p>
        </footer>
    </div>
</x-mail-layout>