<div>
    @if ($deleted_rooms_count > 0)
        <x-secondary-button class="text-xs" x-on:click="$dispatch('open-modal', 'view-deleted-rooms')">
            View Removed Rooms
        </x-secondary-button>
    @endif
    
    <x-modal.full name='view-deleted-rooms' maxWidth='sm'>
        <form x-on:rooms-restored.window="show = false" class="p-5 space-y-5 rounded-lg" wire:submit="submit">
            @csrf
            <hgroup>
                <h2 class="text-lg font-bold">Restore Room</h2>
                <p class="text-xs">Toggle the room you want to restore</p>
            </hgroup>
    
            <div class="space-y-1">
                @foreach ($deleted_rooms as $room)
                    <x-form.checkbox-toggle wire:key="{{ $room->id }}" id="{{ $room->id }}" name="deleted_rooms" wire:click='toggleRoom({{ $room->id }})'>
                        <div class="flex justify-between p-3">
                            <hgroup>
                                <h3 class="font-semibold">{{ $room->room_number }}</h3>
                                <p class="text-xs">{{ $room->building->name }}</p>
                            </hgroup>
                        </div>
                    </x-form.checkbox-toggle>
                @endforeach
                <x-form.input-error field="selected_rooms" />
            </div>
    
            <x-primary-button>
                Restore Rooms
            </x-primary-button>
        </form>
    
    </x-modal.full>
</div>