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
            <h1 class="text-lg"><span class="font-bold">We hope you had an amazing stay, <span class="capitalize">{{ $reservation->user->first_name }}</span>!</h1>

            <div class="space-y-5">
                <p>Hi <span class="capitalize">{{ $reservation->user->first_name }}</span>!</p>

                <p>We hope you had a wonderful stay with us at Amazing View Mountain Resort!</p>
                
                <p>Thank you for choosing our resort and giving us the pleasure of serving you. It was our privilege to ensure your comfort and satisfaction.</p>
                
                <p>We'd love to hear about your experience. Your feedback helps us continuously improve our services. Please take a moment to share your thoughts on our Facebook Page.</p>
                
                <p>If you plan another visit or need further assistance, please don't hesitate to contact us. We look forward to welcoming you back soon!</p>

                <p>
                    <span class="font-semibold">Best Regards</span>, <br />
                    Amazing View Mountain Resort Management
                </p>
            </div>

            <x-info-message>
                Attached on this email is your Reservation Invoice. If you have any concerns or questions, feel free to contact us at <span class="font-semibold">{{ $settings['site_phone'] }}</span> or email us at <span class="font-semibold">{{ $settings['site_email'] }}</span>. 
            </x-info-message>
        </main>
    </div>
</x-mail-layout>
