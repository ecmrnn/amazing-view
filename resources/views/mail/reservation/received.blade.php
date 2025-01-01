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
            <h1 class="text-md"><span class="font-bold">Reservation ID:</span> {{ $reservation->rid }}</h1>

            <p>Good day, John! We're excited to confirm your reservation with us. Here are the details of your reservation:</p>

            <h2 class="font-bold">Guest Details</h2>

            <div>
                <p><strong class="font-bold">Name:</strong> <span class="capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span></p>
                <p><strong class="font-bold">Contact Number:</strong> {{ $reservation->phone }}</p>
                <p><strong class="font-bold">Email:</strong> {{ $reservation->email }}</p>
                <p><strong class="font-bold">Address:</strong> {{ $reservation->address }}</p>
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
                                <th class="px-3 py-2 font-bold text-left bg-slate-200">Room</th>
                                <th class="px-3 py-2 font-bold text-left bg-slate-200">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservation->rooms as $room)
                                <tr class="border-b border-slate-200 last:border-b-0">
                                    <td class="px-3 py-2">{{ $room->building->prefix . ' ' . $room->room_number }}</td>
                                    <td class="px-3 py-2"><x-currency /> {{ number_format($room->rate, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if (count($reservation->amenities) > 0)
                <div class="mt-5">
                    <h2 class="font-bold">Special Requests or Amenities</h2>
                    
                    <ul class="mt-5">
                        @forelse ($reservation->amenities as $amenity)
                            <li key="{{ $room->id }}" class="ml-3 list-disc">
                                {{ $amenity->name }} - <x-currency /> {{ number_format($amenity->price, 2) }}
                            </li>
                        @empty
                            <li class="text-xs opacity-50">No request or amenity selected</li>
                        @endforelse
                    </ul>
                </div>
            @endif


            <h2 class="text-lg md:*:text-2xl font-bold text-blue-500">Total Amount Due: <x-currency /> {{ number_format($reservation->invoice->total_amount, 2) }}</h2>

            @if (!empty($reservation->expires_at))
                <div>
                    <h2 class="font-bold">Payment Methods</h2>
                    <p>To confirm your reservation, a minimum amount of Php500.00 must be paid in any of the payment method below on or before <strong class="font-bold text-red-500">{{ date_format(date_create($reservation->expires_at), 'F d, Y \a\t h:i A') }}</strong>:</p>
                </div>

                <div>
                    <h3 class="font-bold">GCash:</p>
                    <p class="font-normal"><strong class="font-bold">GCash Number:</strong> 09171399334</p>
                    <p class="font-normal"><strong class="font-bold">Account Name:</strong> Fabio Basbaño</p>
                </div>
            @else
                <div class="px-3 py-2 mt-5 text-yellow-700 bg-yellow-100 border border-yellow-500 rounded-md">
                    <p class="font-bold">Proof of Payment Uploaded</p>
                    <p>We have received the image you uploaded on the reservation form. Please wait for our receptionist to confirm your reservation.</p>
                </div>
            @endif
        </main>
    
        <footer>
            <p class="text-center">💖</p>
            <p class="text-center">Thank you for choosing</p>
            <p class="font-bold text-center text-blue-500">Amazing View Mountain Resort!</p>
        </footer>
    </div>
</x-mail-layout>