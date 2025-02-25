<x-guest-layout>
    {{-- Landing Page --}}
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl mx-auto rounded-lg place-items-center">
            <div class="space-y-5 text-center text-white">
                <x-h1>
                    {!! $heading !!}
                </x-h1>
        
                <p class='max-w-sm mx-auto'>
                    {!! $subheading !!}
                </p>
        
                <a class="block" href="#rooms">
                    <x-primary-button>Explore our Rooms</x-primary-button>
                </a>
            </div>

            <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                style="background-image: url({{ asset('storage/' . $rooms_hero_image) }});
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
            @foreach ($available_rooms as $room)
                <div class="space-y-5">
                    <div>
                        <x-img-lg src="{{ $room->image_1_path }}" />
                        
                        <div class="grid grid-cols-3 gap-1 mt-1">
                            <x-img-lg src="{{ $room->image_2_path }}" />
                            <x-img-lg src="{{ $room->image_3_path }}" />
                            <x-img-lg src="{{ $room->image_4_path }}" />
                        </div>
                    </div>
                    
                    <hgroup>
                        <h3 class="text-2xl font-semibold">{{ $room->name }}</h3>
                        <span class="font-semibold"><x-currency />{{ number_format($room->max_rate, 2) }} / night</span>
                    </hgroup>
                    
                    <p class="text-justify indent-8">{{ $room->description }}</p>
                    
                    <a class="block" href="{{ route('guest.reservation') }}" wire:navigate.hover>
                        <x-primary-button>Book this Room</x-primary-button>
                    </a>
                </div>
            @endforeach
        </div>
    </x-section>
</x-guest-layout>