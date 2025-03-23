<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Rooms') }}
                </h1>
                <p class="text-xs">Manage your rooms here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <livewire:app.room.edit-room room="{{ $room->id }}" />

    <x-modal.full name='change-status-modal' maxWidth='sm'>
        <livewire:app.room.change-status :room="$room" />
    </x-modal.full>

    <x-modal.full name='disable-room-modal' maxWidth='sm'>
        <livewire:app.room.disable-room :room="$room" />
    </x-modal.full>
</x-app-layout>