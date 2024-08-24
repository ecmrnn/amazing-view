<x-guest-layout>
    {{-- Landing Page --}}
    <div class="max-w-screen-xl mx-auto grid place-items-center h-screen">
        <x-web.rooms.landing />
    </div>

    {{-- Rooms List --}}
    <x-section id="rooms">
        <x-slot:heading>Amazing Rooms</x-slot:heading>
        <x-slot:subheading>Experience elegant comfort through our rooms!</x-slot:subheading>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <x-web.rooms.room />
            <x-web.rooms.room />
            <x-web.rooms.room />
        </div>
    </x-section>
</x-guest-layout>