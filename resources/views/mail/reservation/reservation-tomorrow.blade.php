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
            <p style="margin: 0; font-size: 16px; font-weight: bold;">Reservations for tomorrow, {{ date_format(date_create($report->start_date), 'F j, Y') }}</p>

            <p style="margin: 0; margin-top: 20px; font-size: 14px;">Attached below is a report for your incoming reservations for tommorrow, you may use this report as a backup in case of system maintenance or loss of internet connection.</p>
        </tr>

        {{-- Footer --}}
        <tr>
            <p style="font-size: 14px; margin: 0; text-align: center;">🤖</p>
            <p style="font-size: 14px; margin: 0; text-align: center;">This is a system generated report</p>
            <p style="font-size: 14px; margin: 0; text-align: center; font-weight: bold; color: #2b7fff">Amazing View Mountain Resort</p>
        </tr>
    </table>
</x-mail-layout>
