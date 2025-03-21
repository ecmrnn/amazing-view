<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">Rooms</h1>
                <p class="text-xs">Manage your rooms here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <livewire:app.room-type.create-room-type />
</x-app-layout>
