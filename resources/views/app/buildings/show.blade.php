<x-app-layout>
    <x-slot:header>
        <hgroup>
            <h1 class="text-xl font-bold leading-tight text-gray-800">Buildings</h1>
            <p class="text-xs">Manage your buildings here</p>
        </hgroup>
    </x-slot:header>

    <livewire:app.buildings.show-building :building="$building" />

    <x-modal.full name='disable-rooms-modal' maxWidth='sm'>
        <livewire:app.buildings.disable-rooms :building="$building" />
    </x-modal.full>

    <x-modal.full name='enable-rooms-modal' maxWidth='sm'>
        <livewire:app.buildings.enable-rooms :building="$building" />
    </x-modal.full>
</x-app-layout>