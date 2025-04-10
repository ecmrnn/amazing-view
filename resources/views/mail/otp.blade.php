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
            <h1 class="font-semibold text-center text-md">Your OTP</h1>

            <p class="py-10 text-3xl font-bold tracking-wide text-center text-blue-500 rounded-md md:text-6xl bg-slate-50">{{ $otp->otp }}</p>

            <p class="max-w-xs mx-auto text-sm text-center"><strong class="block text-red-500">Do not share your OTP!</strong> If this is not you make sure to contact our staff to secure your reservation. Your OTP is valid only for 10 minutes.</p>
        </main>
    
        <footer>
            <p class="text-center">💖</p>
            <p class="text-center">Thank you for choosing</p>
            <p class="font-bold text-center text-blue-500">Amazing View Mountain Resort!</p>
        </footer>
    </div>
</x-mail-layout>