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
            <p style="margin: 0; font-size: 16px; font-weight: bold;">We hope you had an amazing stay, <span style="text-transform: capitalize;">{{ $reservation->user->first_name }}</span>!</p>

            <p style="font-size: 14px;">Hi <span style="text-transform: capitalize;">{{ $reservation->user->first_name }}</span>!</p>

            <p style="font-size: 14px;">We hope you had a wonderful stay with us at Amazing View Mountain Resort!</p>
            
            <p style="font-size: 14px;">Thank you for choosing our resort and giving us the pleasure of serving you. It was our privilege to ensure your comfort and satisfaction.</p>
            
            <p style="font-size: 14px;">We'd love to hear about your experience. Your feedback helps us continuously improve our services. Please take a moment to share your thoughts on our Facebook Page.</p>
            
            <p style="font-size: 14px;">If you plan another visit or need further assistance, please don't hesitate to contact us. We look forward to welcoming you back soon!</p>

            <p style="font-size: 14px;">
                <span style="font-weight: bold;">Best Regards</span>, <br />
                Amazing View Mountain Resort Management
            </p>

            <div style="padding: 20px; border: 1px solid #2b7fff; border-radius: 8px; background-color: #eff6ff; color: #193cb8;">
                <p style="font-size: 16px; font-weight: bold;">Check your Invoice</p>
                <p style="font-size: 14px; margin: 0;">Attached on this email is your Reservation Invoice. If you have any concerns or questions, feel free to contact us at <span style="font-weight: bold;">{{ $settings['site_phone'] }}</span> or email us at <span style="font-weight: bold;">{{ $settings['site_email'] }}</span>.</p>
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
