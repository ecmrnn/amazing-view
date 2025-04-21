<x-guest-layout>
    {{-- Landing Page --}}
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl mx-auto rounded-lg place-items-center">
            <div class="space-y-5 text-center text-white">
                <x-h1>
                    {!! nl2br(e($contents['rooms_heading'] ?? '')) !!}
                </x-h1>
        
                <p class='max-w-sm mx-auto'>
                    {!! $contents['rooms_subheading'] ?? '' !!}
                </p>
        
                <a class="block" href="#rooms">
                    <x-primary-button>Explore our Rooms</x-primary-button>
                </a>
            </div>

            <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                style="background-image: url({{ asset('storage/' . $medias['rooms_hero_image']) }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>

    {{-- Rooms List --}}
    <x-section id="rooms" class="bg-white">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-open"><path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 12v.01"/><path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/></svg></x-slot:icon>
        <x-slot:heading>Amazing Rooms</x-slot:heading>
        <x-slot:subheading>Experience elegant comfort through our rooms!</x-slot:subheading>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($rooms as $room)
                <div class="space-y-5">
                    <div class="relative flex items-center gap-5">
                        <h3 class="text-2xl font-semibold">{{ $room->name }}</h3>
                        @if ($most_popular == $room->id)
                            <p class="px-2 py-1 text-xs font-semibold text-yellow-800 border border-yellow-500 rounded-md bg-yellow-50">Most Popular!</p>
                        @endif
                    </div>
                    <p class="text-justify indent-8">{{ $room->description }}</p>
                    
                    <div>
                        <x-img :zoomable="true" src="{{ $room->image_1_path }}" />
                        
                        <div class="grid grid-cols-3 gap-1 mt-1">
                            <x-img :zoomable="true" src="{{ $room->image_2_path }}" />
                            <x-img :zoomable="true" src="{{ $room->image_3_path }}" />
                            <x-img :zoomable="true" src="{{ $room->image_4_path }}" />
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2 text-blue-800 border border-blue-500 rounded-md bg-blue-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag-icon lucide-tag"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold"><x-currency />{{ number_format($room->max_rate, 2) }}</p>
                            <p class="text-xs">Max. rate per night:</p>
                        </div>
                    </div>

                    @if ($room->inclusions->count() > 0)
                        <div class="flex items-center gap-3">
                            <div class="p-2 text-blue-800 border border-blue-500 rounded-md bg-blue-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles-icon lucide-sparkles"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold">Inclusions</p>
                                <p class="text-xs">This room includes:</p>
                            </div>
                        </div>
                        <div class="p-5 text-blue-800 border border-blue-500 rounded-md bg-blue-50">
                            <ul class="space-y-2 list-disc list-inside">
                                @foreach ($room->inclusions as $inclusion)
                                    <li class="text-sm">{{ $inclusion->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <a class="block" href="{{ route('guest.reservation') }}" wire:navigate.hover>
                        <x-primary-button>Book this Room</x-primary-button>
                    </a>
                </div>
            @endforeach
        </div>
    </x-section>
</x-guest-layout>