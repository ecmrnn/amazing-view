<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Rooms') }}
                </h1>
                <p class="text-xs">Manage your rooms here</p>
            </hgroup>

            @can('create room')
                <x-primary-button class="text-xs" x-on:click="$dispatch('open-modal', 'add-room-modal')">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span>Add Room</span>
                    </div>
                </x-primary-button>
            @endcan
        </div>
    </x-slot:header>

    {{-- Room Cards --}}
    <livewire:app.cards.room-cards :roomType="$room->id" />

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <div class="flex items-center gap-3 sm:gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.rooms.index') }}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
            <hgroup>
                <h2 class="text-lg font-semibold">{{ $room->name }} Rooms</h2>
                <p class="max-w-sm text-xs">Manage all your <strong>{{ $room->name }}</strong> rooms using the table below.</p>
            </hgroup>
        </div>
    
        {{-- Room Table --}}
        <livewire:tables.room-table
            :room_type_id="$room->id"
        />

        {{-- Removed Rooms --}}
        <livewire:app.room.show-deleted-rooms room="{{ $room->id }}" />

        {{-- Modals --}}
        <x-modal.full name='add-room-modal' maxWidth='lg'>
            <livewire:app.room.create-room room="{{ $room->id }}" />
        </x-modal.full>
    </div>
</x-app-layout>