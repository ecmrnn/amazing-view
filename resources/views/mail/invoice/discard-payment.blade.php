<x-mail-layout>
    <div class="max-w-2xl p-5 m-5 mx-auto bg-white rounded-lg shadow-lg md:p-10">
        <header class="flex flex-col items-center gap-5 md:flex-row">
            <div class="w-full max-w-24 aspect-square">
                <img src="{{ $message->embed(storage_path('app/public/' . Arr::get($settings, 'site_logo', 'global/application-logo.png'))) }}">
            </div>
        
            <hgroup>
                <p class="text-lg font-bold text-center md:text-left">Amazing View Mountain Resort</p>
                <p class="text-sm text-center md:text-left">Little Baguio, Paagahan Mabitac, Laguna, Philippines</p>
            </hgroup>
        </header>
    
        <main class="py-10 space-y-5">
            <h1 class="text-md"><span class="font-bold">Proof of Payment Discarded</h1>

            <p>Good day, <span class="capitalize">{{ $invoice->reservation->user->first_name . ' ' . $invoice->reservation->user->last_name }}</span>! We're sorry to inform you that the image or receipt of payment you have submitted has been discarded. Please submit another image within the next hour to process your reservation.</p>

            <h2 class="font-bold">How to submit?</h2>

            <div class="p-5 border rounded-md border-slate-200 bg-slate-50">
                <ul class="list-disc list-inside">
                    <li>You may send your proof of payment at {{ $settings['site_email'] ?? 'reservation@amazingviewresort.com' }} and wait for our receptionist to verify it.</li>
                    <li>You may access or create your guest account using your email {{ $invoice->reservation->user->email }}. If you cannot remember or does have a password yet, click <a class="text-blue-500 underline underline-offset-2" href="{{ route('password.reset', ['token' => $token, 'email' => $invoice->reservation->user->email]) }}">here</a>.</li>
                    <li>You may also use the '<a class="text-blue-500 underline underline-offset-2" href="{{ route('guest.search', ['reservation_id' => $invoice->reservation->rid]) }}">Find Reservation</a>' feature on our website and enter your Reservation ID, wait for the OTP that will be sent to your email then submit your payment.</li>
                </ul>
            </div>

            <x-warning-message>
                <div class="mb-5">
                    <h2 class="font-bold">Payment Methods</h2>
                    <p>To confirm your reservation, a minimum amount of Php500.00 must be paid in the payment method below on or before <strong class="font-bold text-red-500">{{ date_format(date_create($invoice->reservation->expires_at), 'F d, Y \a\t h:i A') }}</strong>:</p>
                </div>

                <div>
                    <h3 class="font-bold">GCash:</p>
                    <p class="font-normal"><strong class="font-bold">GCash Number:</strong> {{ Arr::get($settings, 'site_gcash_phone', '09171399334') }}</p>
                    <p class="font-normal"><strong class="font-bold">Account Name:</strong> {{ Arr::get($settings, 'site_gcash_name', 'Fabio Basbaño') }}</p>
                </div>
            </x-warning-message>
        </main>
    
        <footer>
            <p class="text-center">💖</p>
            <p class="text-center">Thank you for choosing</p>
            <p class="font-bold text-center text-blue-500">Amazing View Mountain Resort!</p>
        </footer>
    </div>
</x-mail-layout>