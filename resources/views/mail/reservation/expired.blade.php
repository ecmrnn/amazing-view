<x-mail-layout>
    <div class="max-w-2xl p-5 mx-auto bg-white md:shadow-lg md:p-10 md:rounded-lg">
        <header class="flex flex-col items-center gap-5 md:flex-row">
            <div class="w-full max-w-24 aspect-square">
                <img src="{{ $message->embed(asset('storage/' . Arr::get($settings, 'site_logo', 'global/application-logo.png'))) }}">
            </div>
        
            <hgroup>
                <p class="text-lg font-bold text-center md:text-left">Amazing View Mountain Resort</p>
                <p class="text-sm text-center md:text-left">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
            </hgroup>
        </header>
    
        <main class="py-10 space-y-5">
            <h1 class="text-lg"><span class="font-bold">Your reservation has expired!</h1>

            <p>Hi <span class="capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>!
                <br /><br />
                We hope this message finds you well. We are writing to inform you that your reservation at Amazing View Mountain Farm Resort has expired.
                <br /><br />
                If you think this is a mistake, kindly contact us immediately to resolve this issue.
            </p>

            <h2 class="font-bold">Expired Reservation Details</h2>

            <div>
                <p><strong class="font-bold">Reservation ID:</strong> {{ $reservation->rid }}</p>
                <p><strong class="font-bold">Reservation Date:</strong> {{ date_format(date_create($reservation->created_at), 'F j, Y - h:i A') }}</p>
                <p><strong class="font-bold">Expiration Date:</strong> {{ date_format(date_create($reservation->expires_at), 'F j, Y - h:i A') }}</p>
            </div>

            <div>
                As a reminder, our policy states that reservations must be confirmed or modified within a certain period prior to the check-in date. Unfortunately, we did not receive a payment confirmation or modification request from you before the expiration date.
            </div>

            <div class="p-5 space-y-5 rounded-md bg-slate-50">
                <p class="font-semibold">Next Steps:</p>

                <div>
                    <ul class="list-disc list-inside list-item">
                        <li><span class="font-semibold">Rebooking:</span> If you would still like to stay with us, we encourage you to rebook your reservation. You can do so by visiting our website or contacting us directly.</li>
                        <li><span class="font-semibold">Assistance:</span> If you need assistance with rebooking or have any questions, please do not hesitate to reach out to our customer support team at <span class="font-semibold">{{ Arr::get($settings, 'site_email', 'info@amazingviewmountainresort.com') }}</span> or give us a call at <span class="font-semibold">{{ Arr::get($settings, 'site_phone', '09171399334') }}</span>.</li>
                    </ul>
                </div>
            </div>

            <p>We value your interest in staying at Amazing View Mountain Farm Resort and hope to welcome you soon. Thank you for your understanding.</p>

            <div>
                <p class="font-bold">Best regards,</p>
                <p>Amazing View Mountain Resort Management</p>
            </div>
        </main>
    
        <footer>
            <p class="text-center">💖</p>
            <p class="text-center">Thank you for choosing</p>
            <p class="font-bold text-center text-blue-500">Amazing View Mountain Resort!</p>
        </footer>
    </div>
</x-mail-layout>