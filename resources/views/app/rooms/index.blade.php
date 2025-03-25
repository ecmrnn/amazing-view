<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">Rooms</h1>
                <p class="text-xs">Manage your rooms here</p>
            </hgroup>

            <div class="flex items-center">
                @can('create room')
                    <x-tooltip text="Add Room" dir="bottom">
                        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'add-room-modal')">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </div>
                        </x-icon-button>
                    </x-tooltip>
                @endcan
                <div class="w-[1px] h-1/2 bg-gray-300"></div>
            </div>
        </div>
    </x-slot:header>

    <div>
        <livewire:app.room.show-rooms :room="$room" />
        
        {{-- Modals --}}
        <x-modal.drawer name='add-room-modal' maxWidth='lg'>
            <livewire:app.room.create-room room="{{ $room->id }}" />
        </x-modal.drawer>
    </div>
</x-app-layout>