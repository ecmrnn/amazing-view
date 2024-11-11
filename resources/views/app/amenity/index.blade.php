<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Amenities') }}
                </h1>
                <p class="text-xs">Manage your amenities and services here</p>
            </hgroup>

            <x-primary-button class="text-xs" x-on:click="$dispatch('open-modal', 'add-amenity-modal')">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    <span>Add Amenity</span>
                </div>
            </x-primary-button>
        </div>
    </x-slot:header>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div class="p-5 space-y-5 bg-white rounded-lg">
            <div>
                <h2 class="font-semibold">Amenities</h2>
                <p class="text-xs">Manage your amenities </p>
            </div>

            <livewire:tables.amenity-table />
        </div>
        <div class="p-5 space-y-5 bg-white rounded-lg">
            <div>
                <h2 class="font-semibold">Services</h2>
                <p class="text-xs">Manage your services </p>
            </div>

            <livewire:tables.service-table />
        </div>
    </div>

    {{-- Modal --}}
    <x-modal.full name='add-amenity-modal' maxWidth='lg'>
        <livewire:app.amenity.create-amenity />
    </x-modal.full>
</x-app-layout>