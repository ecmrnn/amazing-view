<x-guest-layout>
    {{-- Landing Page --}}
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl py-20 mx-auto rounded-lg place-items-center">
            <div class="flex flex-col items-start justify-between w-full p-5 text-center text-white md:flex-row md:text-left">
                <div class="space-y-5">
                    <x-h1>
                        {!! $heading !!}
                    </x-h1>
                    <p class="max-w-sm mx-auto md:mx-0">
                        {!! $subheading !!}
                    </p>

                    <div class="flex gap-1">
                        <a class="inline-block" href="#form">
                            <x-primary-button>Get Started!</x-primary-button>
                        </a>
                        <a class="inline-block" href="{{ route('guest.search') }}" wire:navigate>
                            <x-secondary-button>Find my Reservation</x-secondary-button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                style="background-image: url({{ asset('storage/' . $reservation_hero_image) }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>

    <div id="form" class="min-h-screen px-5 pb-20 bg-white">
        <section x-ref="form">
            <livewire:guest.reservation-form />
        </section>
    </div>
</x-guest-layout>
