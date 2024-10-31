<x-guest-layout>
    {{-- Landing Page --}}
    <div class="grid h-screen max-w-screen-xl mx-auto place-items-center">
        <div class="space-y-5 text-center">
            <x-h1>
                {!! $heading !!}
            </x-h1>
        
            <p class="max-w-xs mx-auto">
                {{ $subheading }}
            </p>
        
            <div class="flex items-center justify-center gap-1">
                <a href="#services">
                    <x-secondary-button>Explore more</x-secondary-button>
                </a>
                <a href="/reservation" wire:navigate>
                    <x-primary-button>Book a Room</x-primary-button>
                </a>
            </div>
        </div>
    </div>

    {{-- Featured Services --}}
    <x-section class="min-h-screen" id="services">
        <x-slot:heading>Featured Services</x-slot:heading>
        <x-slot:subheading>Experience our featured services!</x-slot:subheading>

        <div class="grid gap-5 sm:grid-cols-3">
            @foreach ($featured_services as $key => $featured_service)
                <div class="space-y-5">
                    <x-img-lg src="{{ $featured_service->image }}" />
                
                    <hgroup>
                        <span class="text-xs">{{ sprintf("%02d", $key + 1) }}</span>
                        <h3 class="text-2xl font-semibold">{{ $featured_service->title }}</h3>
                    </hgroup>
                
                    <p class="text-justify">{{ $featured_service->description }}</p>
                </div>
            @endforeach
        </div>
    </x-section>

    {{-- Brief Background --}}
    <x-section class="bg-slate-50">
        <x-slot:heading>Amazing View Mountain Resort</x-slot:heading>
        <x-slot:subheading>Book your dream getaway!</x-slot:subheading>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-img-lg src="{{ $history_image }}" />

            <div class="space-y-5">
                <h3 class="text-xl font-semibold">Be amaze by our story!</h3>

                <p class="text-justify sm:text-left sm:max-w-sm line-clamp-5">
                    {!! $history !!}
                </p>

                <a href="/about" class="block" wire:navigate>
                    <x-primary-button>Explore our Resort</x-primary-button>
                </a>
            </div>
        </div>
    </x-section>
</x-guest-layout>