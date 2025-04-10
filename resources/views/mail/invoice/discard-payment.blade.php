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
            <p style="font-weight: bold; font-size: 16px;">Proof of Payment Discarded</p>

            <p style="font-size: 14px;">Good day, <span style="text-transform: capitalize;">{{ $invoice->reservation->user->first_name . ' ' . $invoice->reservation->user->last_name }}</span>! We're sorry to inform you that the image or receipt of payment you have submitted has been discarded. Please submit another image within the next hour to process your reservation.</p>

            <p style="font-size: 16px; font-weight: bold;">How to submit?</p>

            <div style="padding: 20px; margin-top: 20px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                <ul style="padding: 0; margin-bottom: 0; margin-top: 20px; list-style-position: inside;">
                    <li style="font-size: 14px; margin: 0;">You may send your proof of payment at {{ $settings['site_email'] ?? 'reservation@amazingviewresort.com' }} and wait for our receptionist to verify it.</li>
                    <li style="font-size: 14px; margin: 0;">You may access or create your guest account using your email {{ $invoice->reservation->user->email }}. If you cannot remember or does have a password yet, click <a style="color: #2b7fff; text-decoration: underline; text-underline-offset: 2px;" href="{{ route('password.reset', ['token' => $token, 'email' => $invoice->reservation->user->email]) }}">here</a>.</li>
                    <li style="font-size: 14px; margin: 0;">You may also use the '<a style="color: #2b7fff; text-decoration: underline; text-underline-offset: 2px;" href="{{ route('guest.search', ['reservation_id' => $invoice->reservation->rid]) }}">Find Reservation</a>' feature on our website and enter your Reservation ID, wait for the OTP that will be sent to your email then submit your payment.</li>
                </ul>
            </div>

            <div style="padding: 20px; border: 1px solid #f0b100; border-radius: 8px; font-size: 16px; background-color: #fefce8; color: #894b00;">
                <div style="margin-bottom: 20px;">
                    <p style="font-size: 16px; font-weight: bold;">Payment Methods</p>
                    <p style="font-size: 14px;">To confirm your reservation, a minimum amount of Php500.00 must be paid in the payment method below on or before <span style="font-weight: bold; color: #ef4444;">{{ date_format(date_create($reservation->expires_at), 'F d, Y \a\t h:i A') }}</span>:</p>
                </div>

                <div>
                    <p style="font-size: 14px; font-weight: bold;">GCash:</p>
                    <p style="font-size: 14px; font-weight: normal;"><span style="font-weight: bold;">GCash Number:</span> {{ Arr::get($settings, 'site_gcash_phone', '09171399334') }}</p>
                    <p style="font-size: 14px; font-weight: normal;"><span style="font-weight: bold;">Account Name:</span> {{ Arr::get($settings, 'site_gcash_name', 'Fabio Basbaño') }}</p>
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