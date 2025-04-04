<x-mail-layout>
    <div class="max-w-2xl p-5 m-5 mx-auto bg-white rounded-lg shadow-lg md:p-10">
        <header class="flex flex-col items-center gap-5 md:flex-row">
            <div class="w-full max-w-24 aspect-square">
                <img src="{{ $message->embed(asset('storage/' . Arr::get($settings, 'site_logo', 'global/application-logo.png'))) }}">
            </div>
        
            <hgroup>
                <p class="text-lg font-bold text-center md:text-left">Amazing View Mountain Resort</p>
                <p class="text-sm text-center md:text-left">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
            </hgroup>
        </header>
    
        <main class="py-10 space-y-5">
            <h1 class="text-md"><span class="font-bold">Reservation ID:</span> {{ $reservation->rid }}</h1>

            <p>Good day, <span class="capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>! We're excited to confirm your reservation with us. Here are the details of your reservation:</p>

            <h2 class="font-bold">Guest Details</h2>

            <div>
                <p><strong class="font-bold capitalize">Name:</strong> <span class="capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span></p>
                <p><strong class="font-bold">Contact Number:</strong> {{ $reservation->user->phone }}</p>
                <p><strong class="font-bold">Email:</strong> {{ $reservation->user->email }}</p>
                <p><strong class="font-bold capitalize">Address:</strong> {{ $reservation->user->address }}</p>
            </div>

            <h2 class="font-bold">Reservation Details</h2>

            <div>
                <p><strong class="font-bold">Check-in Date:</strong> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p><strong class="font-bold">Check-out Date:</strong> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                <p><strong class="font-bold">Number of Guests:</strong> 
                    {{ $reservation->adult_count }} 
                    <span>
                        @if ($reservation->adult_count > 1)
                            Adults
                        @else
                            Adult
                        @endif
                    </span>
                    @if ($reservation->children_count > 0)
                        <span>
                            {{ '& ' . $reservation->children_count }} 
                            @if ($reservation->children_count > 1)
                                Children
                            @else
                                Child
                            @endif
                        </span>
                    @endif
                </p>
                <strong class="block font-bold">Rooms Reserved:</strong>
                <div class="mt-5 overflow-hidden border rounded-md border-slate-200">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-3 py-2 font-bold text-left bg-slate-50">Room</th>
                                <th class="px-3 py-2 font-bold text-left bg-slate-50">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservation->rooms as $room)
                                <tr class="border-b border-slate-200 last:border-b-0">
                                    <td class="px-3 py-2">{{ $room->room_number }}</td>
                                    <td class="px-3 py-2"><x-currency />{{ number_format($room->pivot->rate, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($reservation->services->count() > 0 || $has_amenities)
                <h2 class="font-bold">Additional Services or Amenities Added</h2>

                <div class="overflow-hidden border rounded-md border-slate-200">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 font-bold text-left bg-slate-50">Name</th>
                                <th class="px-3 py-2 font-bold text-left bg-slate-50">Type</th>
                                <th class="px-3 py-2 font-bold text-left bg-slate-50">Qty</th>
                                <th class="px-3 py-2 font-bold text-left bg-slate-50">Price</th>
                                <th class="px-3 py-2 font-bold text-left bg-slate-50">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservation->services as $service)
                                <tr class="border-t border-slate-200">
                                    <td class="px-3 py-2 capitalize">{{ $service->name }}</td>
                                    <td class="px-3 py-2 capitalize">Service</td>
                                    <td class="px-3 py-2 capitalize">1</td>
                                    <td class="px-3 py-2"><x-currency />{{ number_format($service->pivot->price, 2) }}</td>
                                    <td class="px-3 py-2"><x-currency />{{ number_format($service->pivot->price, 2) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($reservation->rooms as $room)
                                @foreach ($room->amenities as $amenity)
                                    <tr class="border-t border-slate-200">
                                        <td class="px-3 py-2 capitalize">{{ $amenity->name }}</td>
                                        <td class="px-3 py-2 capitalize">Amenity</td>
                                        <td class="px-3 py-2 capitalize">{{ $amenity->pivot->quantity }}</td>
                                        <td class="px-3 py-2"><x-currency />{{ number_format($amenity->pivot->price, 2) }}</td>
                                        <td class="px-3 py-2"><x-currency />{{ number_format($amenity->pivot->price * $amenity->pivot->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h2 class="text-lg md:*:text-2xl font-bold text-blue-500">Total Amount Due: <x-currency />{{ number_format($reservation->invoice->sub_total, 2) }}</h2>

            @if (!empty($reservation->expires_at))
                <x-warning-message>
                    <div class="mb-5">
                        <h2 class="font-bold">Payment Methods</h2>
                        <p>To confirm your reservation, a minimum amount of Php500.00 must be paid in the payment method below on or before <strong class="font-bold text-red-500">{{ date_format(date_create($reservation->expires_at), 'F d, Y \a\t h:i A') }}</strong>:</p>
                    </div>
    
                    <div>
                        <h3 class="font-bold">GCash:</p>
                        <p class="font-normal"><strong class="font-bold">GCash Number:</strong> {{ Arr::get($settings, 'site_gcash_phone', '09171399334') }}</p>
                        <p class="font-normal"><strong class="font-bold">Account Name:</strong> {{ Arr::get($settings, 'site_gcash_name', 'Fabio Basbaño') }}</p>
                    </div>
                </x-warning-message>
            @else
                <x-info-message>
                    <p class="font-bold">Proof of Payment Uploaded</p>
                    <p>We have received the image you uploaded on the reservation form. Please wait for our receptionist to confirm your reservation.</p>
                </x-info-message>
            @endif
        </main>
    
        <footer>
            <p class="text-center">💖</p>
            <p class="text-center">Thank you for choosing</p>
            <p class="font-bold text-center text-blue-500">Amazing View Mountain Resort!</p>
        </footer>
    </div>
</x-mail-layout>