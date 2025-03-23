<div class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
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

        <x-actions>
            <x-action-button x-on:click="$dispatch('open-modal', 'add-room-modal'); dropdown = false;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-plus"><path d="M13.22 2.416a2 2 0 0 0-2.511.057l-7 5.999A2 2 0 0 0 3 10v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7.354"/><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M15 6h6"/><path d="M18 3v6"/></svg>
                <p>Add Room</p>
            </x-action-button>
        </x-actions>
    </div>

    <livewire:app.cards.room-cards :roomType="$room->id" />

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        {{-- Room Table --}}
        @if ($room->rooms->count())
            <livewire:tables.room-table
                :room_type_id="$room->id"
            />  
        @else
            <div class="font-semibold text-center">
                <x-table-no-data.rooms />
            </div>
        @endif
    </div>
</div>
