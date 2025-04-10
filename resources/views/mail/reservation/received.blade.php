<x-mail-layout>
    <table style="width: 600px; padding: 20px; margin: 20px auto; background-color: white; border-radius: 20px; border: 1px solid #e2e8f0">
        {{-- Header --}}
        <tr>
            <img src="{{ $message->embed(storage_path('app/public/' . Arr::get($settings, 'site_logo', 'global/application-logo.png'))) }}" style="width: 96px; aspect-ratio:1/1">
            {{-- <p class="mt-5 text-lg font-bold text-center md:text-left">Amazing View Mountain Resort</p>
            <p class="text-sm text-center md:text-left">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p> --}}
        </tr>

        {{-- Main --}}
        {{-- <tr>
            <p class="text-md"><span class="font-bold">Reservation ID:</span> {{ $reservation->rid }}</p>

            <p class="mt-5">Good day, <span class="capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>! We're excited to confirm your reservation with us. Here are the details of your reservation:</p>

            <p class="mt-5 font-bold">Guest Details</p>

            <div class="mt-5">
                <p><span class="font-bold capitalize">Name:</span> <span class="capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span></p>
                <p><span class="font-bold">Contact Number:</span> {{ $reservation->user->phone }}</p>
                <p><span class="font-bold">Email:</span> {{ $reservation->user->email }}</p>
                <p><span class="font-bold capitalize">Address:</span> {{ $reservation->user->address }}</p>
            </div>

            <p class="mt-5 font-bold">Reservation Details</p>

            <div>
                <p><span class="font-bold">Check-in Date:</span> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p><span class="font-bold">Check-out Date:</span> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                <p><span class="font-bold">Number of Guests:</span> 
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

            <div class="space-y-5">
                <hgroup>
                    <h2 class='font-bold'>Account Creation</h2>
                    <p class="text-sm">You may create and access your account by clicking <a class="text-blue-500 underline underline-offset-2" href="{{ route('password.reset', ['token' => $token, 'email' => $reservation->user->email]) }}">here</a> to set your password paired with your email address.</p>
                </hgroup>

                <div>
                    <p class='text-sm'><span class="font-bold">Email</span>: {{ $reservation->user->email }}</p>
                    <p class="text-sm"><span class="font-bold">Password</span>: 
                        <a class="text-blue-500 underline underline-offset-2" href="{{ route('password.reset', ['token' => $token, 'email' => $reservation->user->email]) }}">Set password</a>
                    </p>
                </div>
            </div>
        </tr> --}}

        {{-- Footer --}}
        <tr>
            <p style="text-align: center;">💖</p>
            <p style="text-align: center;">Thank you for choosing</p>
            <p style="text-align: center; font-weight: bold; color: #2b7fff">Amazing View Mountain Resort!</p>
        </tr>
    </table>
</x-mail-layout>