<x-guest-layout>
    {{-- Landing Page --}}
    <div class="max-w-screen-xl mx-auto grid place-items-center h-screen">
        <x-web.home.landing />
    </div>

    {{-- Featured Services --}}
    <x-section class="min-h-screen" id="services">
        <x-slot:heading>Featured Services</x-slot:heading>
        <x-slot:subheading>Experience our featured services!</x-slot:subheading>

        <div class="grid sm:grid-cols-3 gap-5">
            <x-web.home.featured-service />
            <x-web.home.featured-service />
            <x-web.home.featured-service />
        </div>
    </x-section>

    {{-- Brief Background --}}
    <x-section class="bg-slate-50">
        <x-slot:heading>Amazing View Mountain Resort</x-slot:heading>
        <x-slot:subheading>Book your dream getaway!</x-slot:subheading>

        <div class="grid sm:grid-cols-2 gap-5">
            <x-web.home.story />
        </div>
    </x-section>
</x-guest-layout>