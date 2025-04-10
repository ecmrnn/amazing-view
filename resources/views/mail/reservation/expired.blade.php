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
            <p style="font-size: 16px;"><span style="font-weight: bold">Your reservation has expired!</span></p>

            <p style="margin: 0; font-size: 14px;">Hi <span style="text-transform: capitalize;">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>!
                <br /><br />
                We hope this message finds you well. We are writing to inform you that your reservation at Amazing View Mountain Farm Resort has expired.
                <br /><br />
                If you think this is a mistake, kindly contact us immediately to resolve this issue.
            </p>

            <p style="font-size: 16px;">Expired Reservation Details</p>

            <div>
                <p style="margin: 0; font-size: 14px;"><strong style="font-weight: bold;">Reservation ID:</strong> {{ $reservation->rid }}</p>
                <p style="margin: 0; font-size: 14px;"><strong style="font-weight: bold;">Reservation Date:</strong> {{ date_format(date_create($reservation->created_at), 'F j, Y - h:i A') }}</p>
                <p style="margin: 0; font-size: 14px;"><strong style="font-weight: bold;">Expiration Date:</strong> {{ date_format(date_create($reservation->expires_at), 'F j, Y - h:i A') }}</p>
            </div>

            <p style="margin: 0; font-size: 14px;">As a reminder, our policy states that reservations must be confirmed or modified within a certain period prior to the check-in date. Unfortunately, we did not receive a payment confirmation or modification request from you before the expiration date.</p>

            <div style="padding: 20px; border-radius: 6px; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                <p style="font-weight: bold; font-size: 16px;">Next Steps:</p>

                <ul style="list-style-position: inside; margin-top: 20px; padding: 0;">
                    <li style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Rebooking:</span> If you would still like to stay with us, we encourage you to rebook your reservation. You can do so by visiting our website or contacting us directly.</li>
                    <li style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Assistance:</span> If you need assistance with rebooking or have any questions, please do not hesitate to reach out to our customer support team at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_email', 'info@amazingviewmountainresort.com') }}</span> or give us a call at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_phone', '09171399334') }}</span>.</li>
                </ul>
            </div>

            <p style="margin: 0; font-size: 14px;">We value your interest in staying at Amazing View Mountain Farm Resort and hope to welcome you soon. Thank you for your understanding.</p>

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