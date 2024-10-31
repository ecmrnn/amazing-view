<x-guest-layout>
    {{-- Landing Page --}}
    <div class="grid h-screen max-w-screen-xl mx-auto place-items-center">
        <div class="space-y-5 text-center">
            <x-h1>
                {!! $heading !!}
            </x-h1>
        
            <p class='max-w-sm mx-auto'>
                {!! $subheading !!}
            </p>
        
            <a class="block" href="#story">
                <x-primary-button>Read our Story</x-primary-button>
            </a>
        </div>
    </div>

    {{-- History --}}
    <x-section id="story">
        <x-slot:heading>Amazing View Mountain Resort</x-slot>
        <x-slot:subheading>A glimpse of our story</x-slot>
        
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <p class="text-justify indent-8">
                    {!! $history !!}
                </p>
            </div>
            
            <x-img-lg src="{{ $history_image }}" />
        </div>
    </x-section>

    {{-- Milestones --}}
    <x-section class="bg-slate-50">
        <x-slot:heading>Our Milestones</x-slot>
        <x-slot:subheading>Recent awards and achievements of our resort</x-slot>

        <div class="grid gap-5 sm:grid-cols-3">
            @foreach ($milestones as $milestone)
                <div class="space-y-5">
                    <x-img-lg src="{{ $milestone->milestone_image }}" />
                
                    <div class="space-y-5">
                        <hgroup>
                            <h3 class="text-xl font-semibold">{{ $milestone->title }}</h3>
                            <span class="inline-block text-sm">Achieved on {{ date_format(date_create($milestone->date_achieved), 'F, j, Y') }}</span>
                        </hgroup>
                
                        <p>{!! $milestone->description !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </x-section>
</x-guest-layout>