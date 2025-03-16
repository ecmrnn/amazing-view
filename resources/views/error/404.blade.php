<x-guest-layout>
    <x-slot:hero>
        <div class="grid h-screen text-center place-items-center">
            <div class="space-y-5 text-white">
                <x-h1>Page not found!</x-h1>
                <p>The page you are looking for is missing!</p>
                <a class="inline-block px-4 py-2 font-semibold text-white transition-all duration-200 ease-in-out bg-blue-500 rounded-lg hover:bg-blue-600" href="{{ route('guest.home') }}" wire:navigate>Back to Home</a>
            </div>

            <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                style="background-image: url({{ asset('storage/global/login.jpg') }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>
</x-guest-layout>