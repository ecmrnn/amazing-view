<x-guest-layout>
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl px-5 pt-20 mx-auto rounded-lg xl:px-0 place-items-center">
            {{-- Nothing to see here... --}}
        </div>
    </x-slot:hero>

    <div id="form" class="max-w-screen-xl min-h-screen px-5 pb-5 mx-auto space-y-5 bg-white">
        <div class="flex flex-col items-center justify-between w-full text-center md:items-start md:flex-row md:text-left">
            <div class="mx-auto space-y-3 text-center">
                <div class="p-3 mx-auto text-blue-800 rounded-md bg-blue-50 w-max">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>

                <x-h1 class="text-blue-500">{{ $contents['find_reservation_heading'] ?? '' }}</x-h1>
                <p class="max-w-sm mx-auto">{{ $contents['find_reservation_subheading'] ?? '' }}</p>
            </div>
        </div>
        
        <section x-ref="form">
            <livewire:guest.find-reservation />
        </section>
    </div>
</x-guest-layout>
