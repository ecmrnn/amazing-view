<x-guest-layout>
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl px-5 pt-20 mx-auto rounded-lg xl:px-0 place-items-center">
            <div class="flex flex-col items-center justify-between w-full text-center md:items-start md:flex-row md:text-left">
                <div class="mx-auto space-y-5 text-center">
                    <x-h1>
                        {{ $contents['find_reservation_heading'] ?? '' }}
                    </x-h1>
                    <p class="max-w-sm mx-auto">{{ $contents['find_reservation_subheading'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </x-slot:hero>

    <div id="form" class="max-w-screen-xl min-h-screen px-5 pb-5 mx-auto bg-white">
        <section x-ref="form">
            <livewire:guest.find-reservation />
        </section>
    </div>
</x-guest-layout>
