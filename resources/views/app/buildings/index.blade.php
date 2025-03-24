<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">Buildings</h1>
                <p class="text-xs">Manage your buildings here</p>
            </hgroup>

            <div class="flex items-center">
                @can('create building')
                    <x-tooltip text="Add Building" dir="bottom">
                        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'add-building-modal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                        </x-icon-button>
                    </x-tooltip>
                @endcan
                <div class="w-[1px] h-1/2 bg-gray-300"></div>
            </div>
        </div>
    </x-slot:header>

    <livewire:app.buildings.show-buildings />

    {{-- Modals --}}
    <x-modal.full name='add-building-modal' maxWidth='md'>
        <livewire:app.buildings.create-building />
    </x-modal.full>
</x-app-layout>