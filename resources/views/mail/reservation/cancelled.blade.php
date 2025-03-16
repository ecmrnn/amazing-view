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
            <h1 class="text-lg"><span class="font-bold">Your reservation has been cancelled.</h1>

            @if ($reservation->cancelled->canceled_by == 'guest')
                <p>Hi <span class="capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span>! We have successfully processed your cancellation request for your reservation at Amazing View Mountain Farm Resort. We are sorry to see you go and hope to welcome you back in the future.</p>
            @else
                <p>Dear <span class="capitalize">{{ $reservation->first_name . ' ' . $reservation->last_name }}</span>, <br /></p>
                <p>We regret to inform you that your reservation has been cancelled by our management. The reason for this cancellation is: <strong class="font-bold">{{ $reservation->cancelled->reason }}</strong>.</p>
                <p>We sincerely apologize for any inconvenience this may cause. If you have any questions or need further assistance, please do not hesitate to reach out to us. We are here to help and hope to have the opportunity to welcome you to Amazing View Mountain Resort in the future.</p>
            @endif

            <h2 class="font-bold">Cancellation Details</h2>

            <div>
                <p><strong class="font-bold">Reservation ID:</strong> {{ $reservation->rid }}</p>
                <p><strong class="font-bold">Check-in Date:</strong> {{ date_format(date_create($reservation->date_in), 'F j, Y') }}</p>
                <p><strong class="font-bold">Cancellation Date:</strong> {{ date_format(date_create($reservation->cancelled->canceled_at), 'F j, Y') }}</p>
                <p><strong class="font-bold">Refund Amount:</strong> <x-currency />{{ number_format($reservation->cancelled->refund_amount, 2) }}</p>
            </div>

            @if ($reservation->cancelled->refund_amount > 0)
                <div class="p-5 rounded-md bg-slate-50">
                    Please reply to this email with your preferred method for receiving the refund amount.
                </div>
            @endif

            <p>If you have any questions or need further assistance, please do not hesitate to email us at <strong class="font bold">{{ Arr::get($settings, 'site_email', 'info@amazingviewmountainresort.com') }}</strong> or give us a call at <strong class="font-bold">{{ Arr::get($settings, 'site_phone', '09171399334') }}</strong>. We hope to welcome you back to Amazing View Mountain Farm Resort in the future. Thank you for understanding.</p>

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