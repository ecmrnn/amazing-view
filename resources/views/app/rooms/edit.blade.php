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

    <div>
        <livewire:app.room.edit-room room="{{ $room->id }}" />
            
        <x-modal.full name='change-status-modal' maxWidth='sm'>
            <livewire:app.room.change-status :room="$room" />
        </x-modal.full>

        <x-modal.full name='disable-room-modal' maxWidth='sm'>
            <livewire:app.room.disable-room :room="$room" />
        </x-modal.full>

        {{-- <x-modal.full name='delete-room-modal' maxWidth='sm'>
            <livewire:app.room.delete-room :room="$room" />
        </x-modal.full> --}}

        <x-modal.full name='add-inclusion-modal' maxWidth='sm'>
            <livewire:app.room.create-inclusion :room="$room" />  
          </x-modal.full>
    </div>
</x-app-layout>