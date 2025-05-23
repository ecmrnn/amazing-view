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
        <livewire:app.room-type.edit-room-type :room_type="$room_type" />
        
        {{-- Modal --}}
        <x-modal.full name='delete-room-type-modal' maxWidth='sm'>
            <livewire:app.room-type.delete-room-type :room_type="$room_type" />
        </x-modal.full>

        <x-modal.full name='add-inclusion-modal' maxWidth='sm'>
            <livewire:app.room-type.create-inclusion :room_type="$room_type" />
        </x-modal.full>
    </div>
</x-app-layout>  