<x-mail-layout>
    <div class="max-w-2xl p-5 mx-auto bg-white md:shadow-lg md:p-10 md:rounded-lg">
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
            <div>
                <h1 class="text-lg"><span class="font-bold">Your reservation has been updated!</h1>
                <p class="text-xs">Updated at: {{ date_format(date_create($reservation->updated_at), 'F j, Y - h:i A') }}</p>
            </div>

            <p>Hi <span class="capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span>! <br/><br /> We are pleased to confirm the updates to your reservation at Amazing View Mountain Farm Resort. Below are the revised details of your stay:</p>

            <h2 class="font-bold">Guest Details</h2>

            <div>
                <p><strong class="font-bold">Name: </strong> <span class="capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span></p>
                <p><strong class="font-bold">Contact Number:</strong> {{ $reservation->phone }}</p>
                <p><strong class="font-bold">Email:</strong> {{ $reservation->email }}</p>
                <p><strong class="font-bold">Address:</strong> {{ $reservation->address }}</p>
            </div>

            <h2 class="font-bold">Reservation Details</h2>

            <div>
                <p><strong class="font-bold">Check-in Date:</strong> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p><strong class="font-bold">Check-out Date:</strong> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                <p><strong class="font-bold">Number of Guests:</strong> {{ $reservation->adult_count }} {{ $reservation->adult_count > 1 ? 'Adults' : 'Adult' }} 
                    @if ($reservation->children_count > 0)
                        {{ '& ' . $reservation->children_count }} {{ $reservation->children_count > 1 ? 'Children' : 'Child' }}
                    @endif
                    @if ($reservation->pwd_count != null || $reservation->senior_count)
                        <span>&lpar;</span>
                            @if ($reservation->pwd_count != null)
                                {{ $reservation->pwd_count }}
                                {{ $reservation->pwd_count > 1 ? 'PWDs': 'PWD' }}
                                {{ $reservation->senior_count == null ?: ', ' }}
                            @endif
                            @if ($reservation->senior_count != null)
                                {{ $reservation->senior_count }}
                                {{ $reservation->senior_count > 1 ? 'Seniors': 'Senior' }}
                            @endif
                        <span>&rpar;</span>
                    @endif
                </p>

                <div>
                    <strong class="font-bold">Rooms Reserved:</strong> 
                    <div class="mt-5 overflow-hidden border rounded-md border-slate-200">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-3 py-2 font-bold text-left bg-slate-200">Room</th>
                                    <th class="px-3 py-2 font-bold text-left bg-slate-200">Building</th>
                                    <th class="px-3 py-2 font-bold text-left bg-slate-200">Floor</th>
                                    <th class="px-3 py-2 font-bold text-left bg-slate-200">Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservation->rooms as $room)
                                    <tr class="border-b border-slate-200 last:border-b-0">
                                        <td class="px-3 py-2">{{ $room->building->prefix . ' ' . $room->room_number }}</td>
                                        <td class="px-3 py-2 capitalize">{{ $room->building->name }}</td>
                                        <td class="px-3 py-2">{{ $room->floor_number }}</td>
                                        <td class="px-3 py-2"><x-currency />{{ number_format($room->pivot->rate, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                                @foreach ($room->amenitiesForReservation($reservation->id)->get() as $amenity)
                                    <tr class="border-t border-slate-200">
                                        <td class="px-3 py-2 capitalize">{{ $room->building->prefix . ' ' . $room->room_number . ' - ' . $amenity->name }}</td>
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

            <div>
                <p class="text-lg font-bold text-blue-500">Total Amount Due: <x-currency />{{ number_format($reservation->invoice->total_amount, 2) }}</p>
                <p class="text-xs">Discounts are not yet applied</p>
            </div>

            <p>If you have any further changes or special requests, please do not hesitate to email us at <strong class="font-bold">{{ Arr::get($settings, 'site_email', 'info@amazingviewmountainresort.com') }}</strong> or give us a call at <strong class="font-bold">{{ Arr::get($settings, 'site_phone', '09171399334') }}</strong>.</p>

            <p class="font-bold">We look forward to welcoming you and ensuring you have a memorable stay with us!</p>
        </main>
    
        <footer>
            <p class="text-center">💖</p>
            <p class="text-center">Thank you for choosing</p>
            <p class="font-bold text-center text-blue-500">Amazing View Mountain Resort!</p>
        </footer>
    </div>
</x-mail-layout>