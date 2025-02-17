<x-mail-layout>
    <div class="max-w-2xl p-5 mx-auto bg-white md:shadow-lg md:p-10 md:rounded-lg">
        <header class="flex flex-col items-center gap-5 md:flex-row">
            <div class="w-full max-w-24 aspect-square">
                <img src="{{ $message->embed(asset('storage/global/application-logo.png')) }}">
            </div>
        
            <hgroup>
                <p class="text-lg font-bold text-center md:text-left">Amazing View Mountain Resort</p>
                <p class="text-sm text-center md:text-left">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
            </hgroup>
        </header>
    
        <main class="py-10 space-y-5">
            <h1 class="text-lg"><span class="font-bold">You have missed your reservation.</h1>

            <p>Hi <span class="capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span>!</p>
                
            <p>We hope this message finds you well. We regret to inform you that we noticed you did not check in for your reservation at Amazing View Mountain Farm Resort on October 26, 2024.</p>

            <h2 class="font-bold">Reservation Details</h2>

            <div>
                <p><strong class="font-bold">Reservation ID:</strong> {{ $reservation->rid }}</p>
                <p><strong class="font-bold">Check-in Date:</strong> {{ $reservation->resched_date_in != null ? date_format(date_create($reservation->resched_date_in), 'F j, Y') : date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
            </div>

            <div class="p-5 space-y-5 rounded-lg bg-slate-50">
                <h2 class="font-semibold">Next Steps:</h2>

                <ul class="list-disc list-inside">
                    <li><span class="font-semibold">Rebooking:</span> If you would still like to stay with us, we encourage you to rebook your reservation. You can do so by visiting our website or contacting our reservation team directly.</li>
                    <li><span class="font-semibold">Assistance:</span> If you need assistance with rebooking or have any questions, please do not hesitate to reach out to our customer support team at <span class="font-semibold">support@amazingview.com</span> or give us a call at <span class="font-semibold">09171399334</span>.</li>
                </ul>
            </div>


            <p>As per our policy, reservations not checked-in by the end of the check-in date are considered <span class="font-semibold">no-shows</span> and <span class="font-semibold">will not receive any refund</span>. We value your patronage and hope to have the opportunity to welcome you to Amazing View Mountain Farm Resort in the future.</p>

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