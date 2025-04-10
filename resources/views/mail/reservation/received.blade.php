<x-mail-layout>
    <table style="width: 600px; padding: 20px; margin: 20px auto; background-color: white; border-radius: 20px; border: 1px solid #e2e8f0">
        {{-- Header --}}
        <tr>
            <img src="{{ $message->embed(storage_path('app/public/' . Arr::get($settings, 'site_logo', 'global/application-logo.png'))) }}" style="width: 96px; display: block; margin: 0 auto; aspect-ratio:1/1">
            <p style="margin-top: 20px; font-size: 18px; text-align: center;">Amazing View Mountain Resort</p>
            <p style="margin-top: 20px; text-align: center;">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
        </tr>

        {{-- Main --}}
        <tr>
            <p style="font-size: 16px;"><span style="font-weight: bold">Reservation ID:</span> {{ $reservation->rid }}</p>

            <p style="margin-top: 20px;">Good day, <span style="text-transform: capitalize;">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>! We're excited to confirm your reservation with us. Here are the details of your reservation:</p>

            <p style="margin-top: 20px; font-weight: bold;">Guest Details</p>

            <div style="margin-top: 20px;">
                <p><span style="font-weight: bold; text-transform: capitalize;">Name:</span> <span>{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span></p>
                <p><span style="font-weight: bold">Contact Number:</span> {{ $reservation->user->phone }}</p>
                <p><span style="font-weight: bold">Email:</span> {{ $reservation->user->email }}</p>
                <p><span style="font-weight: bold; text-transform: capitalize;">Address:</span> {{ $reservation->user->address }}</p>
            </div>

            <p style="margin-top: 20px; font-weight: bold;">Reservation Details</p>

            <div>
                <p><span style="font-weight: bold">Check-in Date:</span> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p><span style="font-weight: bold">Check-out Date:</span> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                <p><span style="font-weight: bold">Number of Guests:</span> 
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

                <p style="margin-top: 20px; font-weight: bold;">Rooms Reserved:</p>

                <div style="margin-top: 20px; overflow: hidden; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <table style="width: 100%;">
                        <thead>
                            <tr style="border-bottom 1px solid #e2e8f0">
                                <th style="padding: 8px 12px; font-weight: bold; background-color: #e2e8f0;">Room</th>
                                <th style="padding: 8px 12px; font-weight: bold; background-color: #e2e8f0;">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservation->rooms as $room)
                                <tr style="border-bottom 1px solid #e2e8f0">
                                    <td style="padding: 8px 12px;">{{ $room->room_number }}</td>
                                    <td style="padding: 8px 12px;"><x-currency />{{ number_format($room->pivot->rate, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($reservation->services->count() > 0 || $has_amenities)
                <h2 style="font-weight: bold">Additional Services or Amenities Added</h2>

                <div style="overflow: hidden; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="padding: 8px 12px; font-weight: bold; text-align: left; background-color: #f8fafc;">Name</th>
                                <th style="padding: 8px 12px; font-weight: bold; text-align: left; background-color: #f8fafc;">Type</th>
                                <th style="padding: 8px 12px; font-weight: bold; text-align: left; background-color: #f8fafc;">Qty</th>
                                <th style="padding: 8px 12px; font-weight: bold; text-align: left; background-color: #f8fafc;">Price</th>
                                <th style="padding: 8px 12px; font-weight: bold; text-align: left; background-color: #f8fafc;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservation->services as $service)
                                <tr style="border-top: 1px solid #e2e8f0;">
                                    <td style="padding: 8px 12px; text-transform: capitalize;">{{ $service->name }}</td>
                                    <td style="padding: 8px 12px; text-transform: capitalize;">Service</td>
                                    <td style="padding: 8px 12px; text-transform: capitalize;">1</td>
                                    <td style="padding: 8px 12px;"><x-currency />{{ number_format($service->pivot->price, 2) }}</td>
                                    <td style="padding: 8px 12px;"><x-currency />{{ number_format($service->pivot->price, 2) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($reservation->rooms as $room)
                                @foreach ($room->amenities as $amenity)
                                    <tr style="border-top: 1px solid #e2e8f0;">
                                        <td style="padding: 8px 12px; text-transform: capitalize;">{{ $amenity->name }}</td>
                                        <td style="padding: 8px 12px; text-transform: capitalize;">Amenity</td>
                                        <td style="padding: 8px 12px; text-transform: capitalize;">{{ $amenity->pivot->quantity }}</td>
                                        <td style="padding: 8px 12px;"><x-currency />{{ number_format($amenity->pivot->price, 2) }}</td>
                                        <td style="padding: 8px 12px;"><x-currency />{{ number_format($amenity->pivot->price * $amenity->pivot->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h2 style="font-size: 24px; font-weight: bold; color: #2b7fff">Total Amount Due: <x-currency />{{ number_format($reservation->invoice->sub_total, 2) }}</h2>

            @if (!empty($reservation->expires_at))
                <x-warning-message>
                    <div style="margin-bottom: 20px;">
                        <h2 style="font-weight: bold;">Payment Methods</h2>
                        <p>To confirm your reservation, a minimum amount of Php500.00 must be paid in the payment method below on or before <strong style="font-weight: bold; color: #ef4444;">{{ date_format(date_create($reservation->expires_at), 'F d, Y \a\t h:i A') }}</strong>:</p>
                    </div>
    
                    <div>
                        <h3 style="font-weight: bold;">GCash:</h3>
                        <p style="font-weight: normal;"><strong style="font-weight: bold;">GCash Number:</strong> {{ Arr::get($settings, 'site_gcash_phone', '09171399334') }}</p>
                        <p style="font-weight: normal;"><strong style="font-weight: bold;">Account Name:</strong> {{ Arr::get($settings, 'site_gcash_name', 'Fabio Basbaño') }}</p>
                    </div>
                </x-warning-message>
            @else
                <x-info-message>
                    <p style="font-weight: bold;">Proof of Payment Uploaded</p>
                    <p>We have received the image you uploaded on the reservation form. Please wait for our receptionist to confirm your reservation.</p>
                </x-info-message>
            @endif

            <div style="margin-top: 20px;">
                <hgroup>
                    <h2 style="font-weight: bold;">Account Creation</h2>
                    <p style="font-size: 14px;">You may create and access your account by clicking <a style="color: #3b82f6; text-decoration: underline;" href="{{ route('password.reset', ['token' => $token, 'email' => $reservation->user->email]) }}">here</a> to set your password paired with your email address.</p>
                </hgroup>

                <div>
                    <p style="font-size: 14px;"><span style="font-weight: bold;">Email</span>: {{ $reservation->user->email }}</p>
                    <p style="font-size: 14px;"><span style="font-weight: bold;">Password</span>: 
                        <a style="color: #2b7fff; text-decoration: underline;" href="{{ route('password.reset', ['token' => $token, 'email' => $reservation->user->email]) }}">Set password</a>
                    </p>
                </div>
            </div>
        </tr>

        {{-- Footer --}}
        <tr>
            <p style="text-align: center;">💖</p>
            <p style="text-align: center;">Thank you for choosing</p>
            <p style="text-align: center; font-weight: bold; color: #2b7fff">Amazing View Mountain Resort!</p>
        </tr>
    </table>
</x-mail-layout>