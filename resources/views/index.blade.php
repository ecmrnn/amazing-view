<x-guest-layout>
    {{-- Landing Page --}}
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl mx-auto rounded-lg place-items-center">
            <div class="space-y-5 text-center text-white">
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
            
            <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                style="background-image: url({{ asset('storage/' . $home_hero_image) }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>

    {{-- Featured Services --}}
    <x-section class="bg-white" id="services">
        <x-slot:heading>Featured Services</x-slot:heading>
        <x-slot:subheading>Experience our featured services!</x-slot:subheading>

        <div class="grid gap-5 sm:grid-cols-3">
            @foreach ($featured_services as $key => $featured_service)
                <div class="space-y-5">
                    @if (!empty($featured_service->image))
                        <x-img-lg src="{{ asset('storage/' . $featured_service->image) }}" />
                    @else
                        <x-img-lg src="https://picsum.photos/id/{{ $featured_service->id + 100 }}/200/300?grayscale" />
                    @endif
                
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
    <x-section class="bg-white/95 backdrop-blur-xl">
        <x-slot:heading>Amazing View Mountain Resort</x-slot:heading>
        <x-slot:subheading>Book your dream getaway!</x-slot:subheading>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-img-lg src="{{ $history_image }}" />

            <div class="space-y-5">
                <h3 class="text-xl font-semibold">Be amaze by our story!</h3>

                <p class="text-justify indent-16">
                    {!! $history !!}
                </p>

                <a href="/about" class="block" wire:navigate>
                    <x-primary-button>Explore our Resort</x-primary-button>
                </a>
            </div>
        </div>
    </x-section>

    {{-- Testimonials --}}
    <x-section class="bg-white">
        <x-slot:heading>Testimonials</x-slot:heading>
        <x-slot:subheading>Hear feedbacks from our previous guests!</x-slot:subheading>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 place-items-start">
            {{-- 2 Chunks --}}
            @forelse ($testimonials->chunk(ceil($testimonials->count() / 2)) as $testimonial_chunks)
                <div class="space-y-4 lg:hidden">
                    @foreach ($testimonial_chunks as $testimonial)
                        <div key="{{ $testimonial->id }}" class="p-4 border rounded-lg border-zinc-200">
                            <p class="text-lg font-semibold text-center">{{ $testimonial->name }}</p>
                            <p class="mt-4 text-sm text-justify indent-4">{{ $testimonial->testimonial }}</p>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="font-semibold text-center opacity-50">
                    No Testimonials Yet
                </div>
            @endforelse

            {{-- 3 Chunks --}}
            @forelse ($testimonials->chunk(ceil($testimonials->count() / 3)) as $testimonial_chunks)
                <div class="hidden space-y-4 lg:block">
                    @foreach ($testimonial_chunks as $testimonial)
                        <div key="{{ $testimonial->id }}" class="p-4 border rounded-lg border-zinc-200">
                            <p class="text-lg font-semibold text-center">{{ $testimonial->name }}</p>
                            <p class="mt-4 text-sm text-justify indent-4">{{ $testimonial->testimonial }}</p>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="font-semibold text-center opacity-50">
                    No Testimonials Yet
                </div>
            @endforelse
        </div>
    </x-section>
</x-guest-layout>