<x-guest-layout>
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl mx-auto rounded-lg place-items-center">
            <div class="flex flex-col items-center justify-between w-full p-5 text-center text-white md:items-start md:flex-row md:text-left">
                <div class="space-y-5">
                    <x-h1>
                        Sorry, we are working on it!
                    </x-h1>
                
                    <p class="max-w-xs mx-auto">
                        The page you are looking for is under maintenance
                    </p>
                
                    <a href="{{ route('guest.home') }}" wire:navigate>
                        <x-primary-button>Back to Home</x-primary-button>
                    </a>
                </div>
                
                <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                    style="background-image: url({{ asset('storage/global/login.jpg') }});
                    background-size: cover;
                    background-position: center;">
                </div>
            </div>
        </div>
    </x-slot:hero>
</x-guest-layout>