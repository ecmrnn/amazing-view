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
            <p style="font-size: 16px;"><span style="font-weight: bold">Reservation ID:</span> {{ $reservation->rid }}</p>

            <p style="margin: 0; font-size: 14px;">Hi <span style="text-transform: capitalize;">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>! After verification of your proof of payment, we would like to inform you about your approved reservation. We have confirmed your payment amounting to 
                <span style="font-weight: bold"><x-currency />{{ number_format($reservation->invoice->payments()->sum('amount'), 2) }}.</span> Your outstanding balance is <strong><x-currency />{{ number_format($reservation->invoice->balance, 2) }}</strong>.
            </p>

            <div>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold">Check-in Date:</span> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold">Check-out Date:</span> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>

                <p style="margin: 0; font-size: 14px; font-weight: bold;">Rooms Reserved:</p> 
                
                <div style="margin-top: 20px; overflow: hidden; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <table>
                        <thead>
                            <tr>
                                <th style="padding: 8px 12px; font-weight: bold; font-size: 14px; margin: 0; background-color: #e2e8f0;">Room Number</th>
                                <th style="padding: 8px 12px; font-weight: bold; font-size: 14px; margin: 0; background-color: #e2e8f0;">Building</th>
                                <th style="padding: 8px 12px; font-weight: bold; font-size: 14px; margin: 0; background-color: #e2e8f0;">Floor</th>
                            </tr>
                        </thead>

                        @foreach ($reservation->rooms as $room)
                            <tr>
                                <td style="margin: 0; font-size: 14px;">{{ $room->room_number }}</td>
                                <td style="margin: 0; font-size: 14px; text-transform: capitalize;">{{ $room->building->name }}</td>
                                <td style="margin: 0; font-size: 14px;">{{ $room->floor_number }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                
                <div style="padding: 20px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                    <p style="margin: 0; font-size: 16px; font-weight: bold">Reminders Upon Arrival</p>
                    <p style="margin: 0; font-size: 14px;">Please present your Reservation ID on our security personnel for verification.</p>
                    
                    <ul style="margin-bottom: 0; margin-top: 20px; list-style-position: inside;">
                        <li style="font-size: 14px; margin: 0;">Actions that violate our rules and regulations will be fairly compensated.</li>
                        <li style="font-size: 14px; margin: 0;">Arrive before or on-time the desired reservation date.</li>
                        <li style="font-size: 14px; margin: 0;">Free parking is available for all guests</li>
                    </ul>
                </div>

                <div style="padding: 20px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                    <p style="margin: 0; font-size: 16px; font-weight: bold">Cancellation Policy</p>
                    <p style="margin: 0; font-size: 14px;">If you need to cancel your reservation you may reach us through this email or any of our contact details below.</p>
                    
                    <div style="margin-top: 15px;">
                        <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">100% Refund</span> - If cancelled on or before {{ date_format($refund_date, 'F j, Y') }}</p>
                        <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">50% Refund</span> - If cancelled after {{ date_format($refund_date, 'F j, Y') }}</p>
                        <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">No Refund</span> - If the guest does not arrive on the scheduled reservation</p>
                    </div>
                </div>
                
                <div>
                    <p>We&apos;ve got everything ready for your arrival. If you have any questions, please don&apos;t hesitate to contact us! Attached below is a copy of your confirmed reservation form.</p>
                    <p class="font-bold">We look forward to providing you with an amazing stay! </p>
                </div>
            </div>
        </tr>

        {{-- Footer --}}
        <tr>
            <p style="font-size: 14px; margin: 0; text-align: center;">💖</p>
            <p style="font-size: 14px; margin: 0; text-align: center;">Thank you for choosing</p>
            <p style="font-size: 14px; margin: 0; text-align: center; font-weight: bold; color: #2b7fff">Amazing View Mountain Resort!</p>
        </tr>
    </table>
</x-mail-layout>