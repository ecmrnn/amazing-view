<x-mail-layout>
    <table style="width: 600px; padding: 20px; margin: 20px auto; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0">
        {{-- Header --}}
        <tr>
            <img src="{{ $message->embed(storage_path('app/public/' . Arr::get($settings, 'site_logo', 'global/application-logo.png'))) }}" style="width: 96px; display: block; margin: 0 auto; aspect-ratio:1/1">
            <p style="margin: 0; font-size: 18px; font-weight: bold; text-align: center;">Amazing View Mountain Resort</p>
            <p style="margin: 0; font-size: 14px; text-align: center;">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
        </tr>

        {{-- Main --}}
        <tr>
            <p style="margin: 0; font-size: 16px; font-weight: bold;">Your reservation has been updated!</p>
            <p style="font-size: 12px; margin-bottom: 20px;">Updated at: {{ date_format(date_create($reservation->updated_at), 'F j, Y - h:i A') }}</p>

            <p style="font-size: 14px;">Hi <span style="text-transform: capitalize;">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>! <br/><br /> We are pleased to confirm the updates to your reservation at Amazing View Mountain Farm Resort. Below are the revised details of your stay:</p>

            <p style="font-size: 16px; font-weight: bold;">Guest Details</p>

            <div>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Name: </span> <span style="text-transform: capitalize;">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span></p>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Contact Number:</span> {{ $reservation->user->phone }}</p>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Email:</span> {{ $reservation->user->email }}</p>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Address:</span> {{ $reservation->user->address }}</p>
            </div>

            <p style="font-size: 16px; font-weight: bold;">Reservation Details</p>

            <div>
                <p style="font-size: 14px; margin: 0;"><span style="text-transform: capitalize;">Check-in Date:</span> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p style="font-size: 14px; margin: 0;"><span style="text-transform: capitalize;">Check-out Date:</span> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                <p style="font-size: 14px; margin: 0;"><span style="text-transform: capitalize;">Number of Guests:</span> {{ $reservation->adult_count }} {{ $reservation->adult_count > 1 ? 'Adults' : 'Adult' }} 
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
                    <p style="text-transform: capitalize; font-weight: bold; font-size: 16px;">Rooms Reserved:</p> 
                    <div style="overflow: hidden; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <th style="padding: 8px 12px; font-weight: bold; font-size: 14px; text-align: left; background-color: #e2e8f0;">Room</th>
                                    <th style="padding: 8px 12px; font-weight: bold; font-size: 14px; text-align: left; background-color: #e2e8f0;">Building</th>
                                    <th style="padding: 8px 12px; font-weight: bold; font-size: 14px; text-align: left; background-color: #e2e8f0;">Floor</th>
                                    <th style="padding: 8px 12px; font-weight: bold; font-size: 14px; text-align: left; background-color: #e2e8f0;">Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservation->rooms as $room)
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 8px 12px; margin: 0; font-size: 14px;">{{ $room->room_number }}</td>
                                        <td style="padding: 8px 12px; margin: 0; font-size: 14px; text-transform: capitalize;">{{ $room->building->name }}</td>
                                        <td style="padding: 8px 12px; margin: 0; font-size: 14px;">{{ $room->floor_number }}</td>
                                        <td style="padding: 8px 12px; margin: 0; font-size: 14px;"><x-currency />{{ number_format($room->pivot->rate, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($reservation->services->count() > 0 || $has_amenities)
                <p style="font-size: 16px; font-weight: bold;">Additional Services or Amenities Added</p>

                <div style="overflow: hidden; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="padding: 8px 12px; font-size: 14px; font-weight: bold; text-align: left; background-color: #e2e8f0;"">Name</th>
                                <th style="padding: 8px 12px; font-size: 14px; font-weight: bold; text-align: left; background-color: #e2e8f0;"">Type</th>
                                <th style="padding: 8px 12px; font-size: 14px; font-weight: bold; text-align: left; background-color: #e2e8f0;"">Qty</th>
                                <th style="padding: 8px 12px; font-size: 14px; font-weight: bold; text-align: left; background-color: #e2e8f0;"">Price</th>
                                <th style="padding: 8px 12px; font-size: 14px; font-weight: bold; text-align: left; background-color: #e2e8f0;"">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservation->services as $service)
                                <tr style="border-top: 1px solid #e2e8f0;">
                                    <td style="padding: 8px 12px; font-size: 14px; text-transform: capitalize;">{{ $service->name }}</td>
                                    <td style="padding: 8px 12px; font-size: 14px; text-transform: capitalize;">Service</td>
                                    <td style="padding: 8px 12px; font-size: 14px; text-transform: capitalize;">1</td>
                                    <td style="padding: 8px 12px; font-size: 14px;"><x-currency />{{ number_format($service->pivot->price, 2) }}</td>
                                    <td style="padding: 8px 12px; font-size: 14px;"><x-currency />{{ number_format($service->pivot->price, 2) }}</td>
                                </tr>
                            @endforeach

                            @foreach ($reservation->rooms as $room)
                                @foreach ($room->amenitiesForReservation($reservation->id)->get() as $amenity)
                                    <tr style="border-top: 1px solid #e2e8f0;">
                                        <td style="padding: 8px 12px; font-size: 14px; text-transform: capitalize;">{{ $amenity->name }}</td>
                                        <td style="padding: 8px 12px; font-size: 14px; text-transform: capitalize;">Amenity</td>
                                        <td style="padding: 8px 12px; font-size: 14px; text-transform: capitalize;">{{ $amenity->pivot->quantity }}</td>
                                        <td style="padding: 8px 12px; font-size: 14px;"><x-currency />{{ number_format($amenity->pivot->price, 2) }}</td>
                                        <td style="padding: 8px 12px; font-size: 14px;"><x-currency />{{ number_format($amenity->pivot->price * $amenity->pivot->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div>
                <p style="font-size: 18px; font-weight: bold; margin: 20px 0; color: #2b7fff">Total Amount Due: <x-currency />{{ number_format($reservation->invoice->sub_total, 2) }}</p>
                <p style="font-size: 14px;">Discounts are not yet applied</p>
            </div>

            <p style="font-size: 14px;">If you have any further changes or special requests, please do not hesitate to email us at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_email', 'info@amazingviewmountainresort.com') }}</span> or give us a call at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_phone', '09171399334') }}</span>.</p>

            <p style="font-size: 14px; font-weight: bold;">We look forward to welcoming you and ensuring you have a memorable stay with us!</p>
        </tr>

        {{-- Footer --}}
        <tr>
            <p style="font-size: 14px; margin: 0; text-align: center;">💖</p>
            <p style="font-size: 14px; margin: 0; text-align: center;">Thank you for choosing</p>
            <p style="font-size: 14px; margin: 0; text-align: center; font-weight: bold; color: #2b7fff">Amazing View Mountain Resort!</p>
        </tr>
    </table>
</x-mail-layout>