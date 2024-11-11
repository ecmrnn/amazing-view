<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Buildings') }}
                </h1>
                <p class="text-xs">Manage your buildings here</p>
            </hgroup>

            <x-primary-button class="text-xs" x-on:click="$dispatch('open-modal', 'add-building-modal')">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    <span>Add Building</span>
                </div>
            </x-primary-button>
        </div>
    </x-slot:header>

    <livewire:app.buildings.show-buildings />

    {{-- Modals --}}
    <x-modal.full name='add-building-modal' maxWidth='md'>
        <livewire:app.buildings.create-building />
    </x-modal.full>
</x-app-layout>