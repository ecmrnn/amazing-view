<x-mail-layout>
    <div class="max-w-2xl p-5 mx-auto bg-white md:shadow-lg md:p-10 md:rounded-lg">
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
            <h1 class="text-lg font-semibold">We have received your request for reservation!</h1>

            <p>Hello, <span class="capitalize">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</span>!</p>
            <p>We are thrilled to inform you that we have received your request to reserve our function hall for your upcoming event. We are excited to be a part of your special occasion!</p>

            <h2 class="font-bold">Reservation Details</h2>

            <div>
                <p><strong class="font-bold">Reservation Date:</strong> {{ date_format(date_create($reservation->reservation_date), 'F j, Y') }}</p>
                <p><strong class="font-bold">Event Name:</strong> {{ $reservation->event_name }}</p>
                <p><strong class="font-bold">Event Description:</strong> {{ $reservation->event_description }}</p>
            </div>

            <x-info-message>Kindly wait for one of the staff of Amazing View Mountain Resort to reach out to you via email to confirm this reservation. If you have any questions, feel free to give us a call or send a response to this email.</x-info-message>

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