<x-guest-layout>
    {{-- Landing Page --}}
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl mx-auto rounded-lg place-items-center">
            <div class="space-y-5 text-center text-white">
                <x-h1>
                    {!! nl2br(e($contents['home_heading'] ?? '')) !!}
                </x-h1>
            
                <p class="max-w-xs mx-auto">
                    {{ $contents['home_subheading'] ?? '' }}
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
                style="background-image: url({{ asset('storage/' . $medias['home_hero_image']) }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>

    {{-- Featured Services --}}
    <x-section class="bg-white" id="services">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/></svg></x-slot:icon>
        <x-slot:heading>Featured Services</x-slot:heading>
        <x-slot:subheading>Experience our featured services!</x-slot:subheading>

        <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
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
    <x-section class="text-white bg-blue-800/75 backdrop-blur-xl">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg></x-slot:icon>
        <x-slot:heading>Amazing View Mountain Resort</x-slot:heading>
        <x-slot:subheading>Book your dream getaway!</x-slot:subheading>

        <div class="flex items-center justify-center gap-5">
            <a href="{{ route('guest.reservation') }}" wire:navigate class="block w-full text-right">
                <x-secondary-button>
                    Book a Room
                </x-secondary-button>
            </a>
            <p>|</p>
            <a href="{{ route('guest.function-hall') }}" wire:navigate class="block w-full">
                <x-secondary-button>
                    Book a Hall
                </x-secondary-button>
            </a>
        </div>
    </x-section>

    {{-- Testimonials --}}
    <x-section class="bg-white">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-heart"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M15.8 9.2a2.5 2.5 0 0 0-3.5 0l-.3.4-.35-.3a2.42 2.42 0 1 0-3.2 3.6l3.6 3.5 3.6-3.5c1.2-1.2 1.1-2.7.2-3.7"/></svg></x-slot:icon>
        <x-slot:heading>Testimonials</x-slot:heading>
        <x-slot:subheading>Read feedbacks from our previous guests!</x-slot:subheading>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3 place-items-start">
            {{-- 2 Chunks --}}
            @forelse ($testimonials->chunk(ceil($testimonials->count() / 2)) as $testimonial_chunks)
                <div class="space-y-5 lg:hidden">
                    @foreach ($testimonial_chunks as $testimonial)
                        <div wire:key="{{ $testimonial->id }}" class="p-5 border rounded-lg border-slate-200 bg-slate-50">
                            <p class="text-lg font-semibold text-center">{{ $testimonial->name }}</p>
                            <div class="flex justify-center gap-1 mt-2">
                                @for ($rating = 0; $rating < 5; $rating++)
                                    @if ($rating < $testimonial->rating)
                                        <div class="text-amber-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                        </div>
                                    @else
                                        <div class="text-zinc-800/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                            <p class="mt-5 text-sm text-justify indent-4">{!! nl2br(e($testimonial->testimonial ?? '')) !!}</p>
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
                <div class="hidden space-y-5 lg:block">
                    @foreach ($testimonial_chunks as $testimonial)
                        <div key="{{ $testimonial->id }}" class="p-5 border rounded-md border-slate-200 bg-slate-50">
                            <p class="text-lg font-semibold text-center">{{ $testimonial->name }}</p>
                            <div class="flex justify-center gap-1 mt-2">
                                @for ($rating = 0; $rating < 5; $rating++)
                                    @if ($rating < $testimonial->rating)
                                        <div class="text-amber-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                        </div>
                                    @else
                                        <div class="text-zinc-800/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                            <p class="mt-5 text-sm text-justify indent-4">{!! nl2br(e($testimonial->testimonial ?? '')) !!}</p>
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