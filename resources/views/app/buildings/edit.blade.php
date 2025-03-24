<x-app-layout>
    <x-slot:header>
        <hgroup>
            <h1 class="text-xl font-bold leading-tight text-gray-800">Buildings</h1>
            <p class="text-xs">Manage your buildings here</p>
        </hgroup>
    </x-slot:header>

    <livewire:app.buildings.edit-building :building="$building" />
</x-app-layout>