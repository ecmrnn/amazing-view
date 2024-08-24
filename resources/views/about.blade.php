<x-guest-layout>
    {{-- Landing Page --}}
    <div class="max-w-screen-xl mx-auto grid place-items-center h-screen">
        <x-web.about.landing />
    </div>

    {{-- History --}}
    <x-section id="story">
        <x-slot:heading>Amazing View Mountain Resort</x-slot>
        <x-slot:subheading>A glimpse of our story</x-slot>
        
        <div class="grid sm:grid-cols-2 gap-5">
            <x-web.about.history />
        </div>
    </x-section>

    {{-- Milestones --}}
    <x-section class="bg-slate-50">
        <x-slot:heading>Our Milestones</x-slot>
        <x-slot:subheading>Recent awards and achievements of our resort</x-slot>

        <div class="grid sm:grid-cols-3 gap-5">
            <x-web.about.milestone />
            <x-web.about.milestone />
            <x-web.about.milestone />
        </div>
    </x-section>
</x-guest-layout>