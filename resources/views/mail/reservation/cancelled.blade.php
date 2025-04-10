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
            <p style="font-size: 16px;"><span style="font-weight: bold">Your reservation has been cancelled.</span></p>

            @if ($reservation->cancelled->canceled_by == 'guest')
                <p style="margin: 0; font-size: 14px;">Hi <span style="text-transform: capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>! We have successfully processed your cancellation request for your reservation at Amazing View Mountain Farm Resort. We are sorry to see you go and hope to welcome you back in the future.</p>
            @else
                <p style="margin: 0; font-size: 14px;">Dear <span style="text-transform: capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>, <br /></p>
                <p style="margin: 0; font-size: 14px;">We regret to inform you that your reservation has been cancelled by our management. The reason for this cancellation is: <span style="font-weight: bold;">{{ $reservation->cancelled->reason }}</span>.</p>
                <p style="margin: 0; font-size: 14px;">We sincerely apologize for any inconvenience this may cause. If you have any questions or need further assistance, please do not hesitate to reach out to us. We are here to help and hope to have the opportunity to welcome you to Amazing View Mountain Resort in the future.</p>
            @endif

            <p style="font-size: 16px; font-weight: bold;">Cancellation Details</p>

            <div>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Reservation ID:</span> {{ $reservation->rid }}</p>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Check-in Date:</span> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Cancellation Date:</span> {{ date_format(date_create($reservation->cancelled->canceled_at), 'F j, Y') }}</p>
                <p style="margin: 0; font-size: 14px;"><span style="font-weight: bold;">Refund Amount:</span> <x-currency />{{ number_format($reservation->cancelled->refund_amount, 2) }}</p>
            </div>

            <p style="font-size: 14px;">If you have any questions or need further assistance, please do not hesitate to email us at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_email', 'info@amazingviewmountainresort.com') }}</span> or give us a call at <span style="font-weight: bold;">{{ Arr::get($settings, 'site_phone', '09171399334') }}</span>. We hope to welcome you back to Amazing View Mountain Farm Resort in the future. Thank you for understanding.</p>

            @if ($reservation->cancelled->refund_amount > 0)
                <div style="padding: 20px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f8fafc">
                    <p style="font-size: 14px; margin: 0;">Please reply to this email with your preferred method for receiving the refund amount.</p>
                </div>
            @endif

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