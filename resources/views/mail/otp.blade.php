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
            <p style="font-weight: bold; text-align: center; font-size: 18px;">Your OTP</p>

            <p style="padding: 40px 0; font-size: 30px; font-weight: bold; letter-spacing: .4px; text-align: center; color: #2b7fff; border-radius: 6px; background-color: #f8fafc; border: 1px solid #e2e8f0;">{{ $otp->otp }}</p>

            <div style="max-width: 320px; width: 100%; text-align: center; margin: 0 auto;">
                <p style="color: #ef4444; font-size: 16px;">Do not share your OTP!</p>
                <p style="font-size: 14px;">If this is not you make sure to contact our staff to secure your reservation. Your OTP is valid only for 10 minutes.</p>
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