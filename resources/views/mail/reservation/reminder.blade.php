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
            <p style="font-size: 16px;"><span style="font-weight: bold">Amazing vacation ahead!</span></p>

            <p style="font-size: 14px;">Hi, <span style="text-transform: capitalize;">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>!
                <br></br>
                We are excited to welcome you to Amazing View Mountain Farm Resort soon! This is a friendly reminder about your upcoming stay with us.
            </p>

            <div>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Check-in Date:</span> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Check-out Date:</span> {{ date_format(date_create($reservation->date_out), 'F j, Y') }}</p>
                <div>
                    <p style="font-weight: bold;">Rooms Reserved:</p> 
                    <div style="overflow: hidden; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <table style="border-collapse: collapse; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="font-size: 14px; text-align: left; padding: 8px 12px;">Room Number</th>
                                    <th style="font-size: 14px; text-align: left; padding: 8px 12px;">Building</th>
                                    <th style="font-size: 14px; text-align: left; padding: 8px 12px;">Floor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservation->rooms as $room)
                                    <tr>
                                        <p style="font-size: 14px; margin: 0; padding: 8px 12px;">{{ $room->room_number }}</p>
                                        <p style="font-size: 14px; margin: 0; padding: 8px 12px; text-transform: capitalize;">{{ $room->building->name }}</p>
                                        <p style="font-size: 14px; margin: 0; padding: 8px 12px;">{{ $room->floor_number }}</p>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div style="padding: 20px; margin-top: 20px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                <p style="margin: 0; font-size: 16px; font-weight: bold">Reminders Upon Arrival</p>
                <p style="margin: 0; font-size: 14px;">Please present your Reservation ID on our security personnel for verification.</p>
                
                <ul style="padding: 0; margin-bottom: 0; margin-top: 20px; list-style-position: inside;">
                    <li style="font-size: 14px; margin: 0;">Actions that violate our rules and regulations will be fairly compensated.</li>
                    <li style="font-size: 14px; margin: 0;">Arrive before or on-time the desired reservation date.</li>
                    <li style="font-size: 14px; margin: 0;">Free parking is available for all guests</li>
                </ul>
            </div>

            <div style="padding: 20px; margin-top: 20px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                <p style="margin: 0; font-size: 16px; font-weight: bold">Cancellation Policy</p>
                <p style="margin: 0; font-size: 14px;">If you need to cancel your reservation you may reach us through this email or any of our contact details below.</p>
                
                <div style="margin-top: 20px;">
                    <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">100% Refund</span> - If cancelled on or before {{ date_format($refund_date, 'F j, Y') }}</p>
                    <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">50% Refund</span> - If cancelled after {{ date_format($refund_date, 'F j, Y') }}</p>
                    <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">No Refund</span> - If the guest does not arrive on the scheduled reservation</p>
                </div>
            </div>

            <p style="margin: 0; font-size: 14px;">We&apos;ve got everything ready for your arrival. If you have any questions, please don&apos;t hesitate to contact us! Attached below is a copy of your confirmed reservation form.</p>
            <p style="margin: 0; font-size: 14px; font-weight: bold;">We look forward to providing you with an amazing stay! </p>
        </tr>

        {{-- Footer --}}
        <tr>
            <p style="font-size: 14px; margin: 0; text-align: center;">💖</p>
            <p style="font-size: 14px; margin: 0; text-align: center;">Thank you for choosing</p>
            <p style="font-size: 14px; margin: 0; text-align: center; font-weight: bold; color: #2b7fff">Amazing View Mountain Resort!</p>
        </tr>
    </table>
</x-mail-layout>