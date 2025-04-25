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
            <p style="font-size: 16px;"><span style="font-weight: bold">We have received your request for reservation!</span></p>

            <p style="font-size: 14px;">Hello, <span style="font-size: capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span>!</p>
            <p style="margin: 0; font-size: 14px;">We are thrilled to inform you that we have received your request to reserve our function hall for your upcoming event. We are excited to be a part of your special occasion!</p>

            <p style="font-size: 14px; font-weight: bold;">Reservation Details</p>

            <div>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Reservation Date:</span> {{ date_format(date_create($reservation->reservation_date), 'F j, Y') }}</p>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Event Name:</span> {{ $reservation->event_name }}</p>
                <p style="font-size: 14px; margin: 0;"><span style="font-weight: bold;">Event Description:</span> {{ $reservation->event_description }}</p>
            </div>

            <div style="padding: 20px; border: 1px solid #2b7fff; border-radius: 8px; background-color: #eff6ff; color: #193cb8;">
                <p style="font-size: 16px; font-weight: bold;">We&apos;ll be in touch!</p>
                <p style="margin: 0; font-size: 14px;">Kindly wait for one of the staff of Amazing View Mountain Resort to reach out to you via email to confirm this reservation. If you have any questions, feel free to give us a call or send a response to this email.</p>
            </div>

            <div style="margin-top: 20px;">
                <p style="margin: 0; font-size: 14px; font-weight: bold;">Best regards,</p>
                <p style="margin: 0; font-size: 14px;">Amazing View Mountain Resort Management</p>
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