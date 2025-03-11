<x-guest-layout>
    {{-- Landing Page --}}
    <x-slot:hero>
        <div class="grid h-full max-w-screen-xl mx-auto rounded-lg place-items-center">
            <div class="space-y-5 text-center text-white">
                <x-h1>
                    {!! $contents['about_heading'] !!}
                </x-h1>
        
                <p class='max-w-sm mx-auto'>
                    {!! $contents['about_subheading'] !!}
                </p>
        
                <a class="block" href="#story">
                    <x-primary-button>Read our Story</x-primary-button>
                </a>
            </div>

            <div class="absolute w-full h-full rounded-lg -z-10 before:contents[''] before:w-full before:h-full before:bg-black/35 before:absolute before:top-0 before:left-0 overflow-hidden"
                style="background-image: url({{ asset('storage/' . $medias['about_hero_image']) }});
                background-size: cover;
                background-position: center;">
            </div>
        </div>
    </x-slot:hero>

    {{-- History --}}
    <x-section id="story" class="bg-white">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-text"><path d="M12 7v14"/><path d="M16 12h2"/><path d="M16 8h2"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/><path d="M6 12h2"/><path d="M6 8h2"/></svg></x-slot>
        <x-slot:heading>Amazing View Mountain Resort</x-slot>
        <x-slot:subheading>A glimpse of our story</x-slot>
        
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <p class="text-justify indent-8">
                    {!! $contents['about_history'] !!}
                </p>
            </div>
            
            <x-img-lg src="{{ asset('storage/' . $medias['about_history_image']) }}" />
        </div>
    </x-section>

    {{-- Milestones --}}
    <x-section class="bg-slate-50">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg></x-slot>
        <x-slot:heading>Our Milestones</x-slot>
        <x-slot:subheading>Recent awards and achievements of our resort</x-slot>

        <div class="grid gap-5 sm:grid-cols-3">
            @foreach ($milestones as $milestone)
                <div class="space-y-5">
                    <x-img-lg src="{{ $milestone->milestone_image }}" />
                
                    <div class="space-y-5">
                        <hgroup>
                            <h3 class="text-xl font-semibold">{{ $milestone->title }}</h3>
                            <span class="inline-block text-sm">Achieved on {{ date_format(date_create($milestone->date_achieved), 'F j, Y') }}</span>
                        </hgroup>
                
                        <p>{!! $milestone->description !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </x-section>
    
    <x-section class="bg-white">
        <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg></x-slot:icon>
        <x-slot:heading>Our Location</x-slot:heading>
        <x-slot:subheading>Waze your way to Amazing View!</x-slot:subheading>

        <div>
            <x-laravel-map
                :initialMarkers="$map['marker']"
                :options="$map['option']"
            />
        </div>
    </x-section>
</x-guest-layout>