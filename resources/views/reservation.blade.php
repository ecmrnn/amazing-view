<x-guest-layout>
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl px-5 py-20 mx-auto rounded-lg xl:px-0 place-items-center">
            <div class="flex flex-col items-center justify-between w-full text-center text-white md:items-start md:flex-row md:text-left">
                <div class="space-y-5">
                    <x-h1>
                        {!! nl2br(e($contents['room_reservation_heading'] ?? '')) !!}
                    </x-h1>
                    <p class="max-w-sm mx-auto md:mx-0">
                        {!! $contents['room_reservation_subheading'] ?? '' !!}
                    </p>

                    <div class="flex justify-center gap-1 md:justify-start">
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
                style="background-image: url({{ asset('storage/' . $medias['room_reservation_hero_image']) }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>

    <div id="form" class="min-h-screen px-5 pb-20 bg-slate-50">
        <section x-ref="form">
            <livewire:guest.reservation-form />
        </section>
    </div>
</x-guest-layout>
