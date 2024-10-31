<x-guest-layout>
    {{-- Landing Page --}}
    <div class="grid h-screen max-w-screen-xl mx-auto place-items-center">
        <div class="space-y-5 text-center">
            <x-h1>
                {!! $heading !!}
            </x-h1>
        
            <p class="max-w-sm mx-auto">
                {!! $subheading !!}
            </p>
        
            <a class="block" href="#rooms">
                <x-primary-button>Explore More</x-primary-button>
            </a>
        </div>
    </div>

    {{-- Rooms List --}}
    <x-section id="rooms">
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
                        <span class="text-sm"><x-currency /> {{ $room->max_rate }} / night</span>
                    </hgroup>
                    
                    <p class="">{{ $room->description }}</p>
                    
                    <a class="block" href="#" wire:navigate>
                        <x-primary-button>More Details</x-primary-button>
                    </a>
                </div>
            @endforeach
        </div>
    </x-section>
</x-guest-layout>