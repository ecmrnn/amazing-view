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
            <p style="font-size: 16px;"><span style="font-weight: bold">You have missed your reservation.</span></p>

            <p style="font-size: 14px;">Hi <span style="font-weight: bold;">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>!</p>

            <p style="font-size: 14px;">We hope this message finds you well. We regret to inform you that we noticed you did not check in for your reservation at Amazing View Mountain Farm Resort on October 26, 2024.</p>

            <p style="font-size: 14px; font-weight: bold;">Reservation Details</p>

            <div>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Reservation ID:</span> {{ $reservation->rid }}</p>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Check-in Date:</span> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
            </div>

            <div style="margin: 20px 0; padding: 20px; border: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 6px;">
                <p style="font-weight: bold; font-size: 16px;">Next Steps:</p>

                <ul style="padding: 0; list-style-position: inside;">
                    <li style="font-size: 14px;"><span style="font-weight: bold;">Rebooking:</span> If you would still like to stay with us, we encourage you to rebook your reservation. You can do so by visiting our website or contacting our reservation team directly.</li>
                    <li style="font-size: 14px;"><span style="font-weight: bold;">Assistance:</span> If you need assistance with rebooking or have any questions, please do not hesitate to reach out to our customer support team at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_email', 'info@amazingviewmountainresort.com') }}</span> or give us a call at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_phone', '09171399334') }}</span>.</li>
                </ul>
            </div>

            <p style="font-size: 14px;">As per our policy, reservations not checked-in by the end of the check-in date are considered <span style="font-weight: bold;">no-shows</span> and <span style="font-weight: bold;">will not receive any refund</span>. We value your patronage and hope to have the opportunity to welcome you to Amazing View Mountain Farm Resort in the future.</p>

            <div>
                <p style="font-size: 14px; font-weight: bold;">Best regards,</p>
                <p style="font-size: 14px;">Amazing View Mountain Resort Management</p>
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