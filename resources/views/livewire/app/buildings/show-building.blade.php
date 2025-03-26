<div class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.buildings.index') }}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
            <hgroup>
                <h2 class="text-lg font-semibold">{{ $building->name}} Rooms</h2>
                <p class="max-w-sm text-xs">Manage all your rooms in this building</p>
            </hgroup>
        </div>

        <x-actions>
            <div class="space-y-1">
                <a href="{{ route('app.buildings.edit', ['building' => $building->id]) }}" wire:navigate>
                    <x-action-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings2-icon lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                        <p>Edit</p>
                    </x-action-button>
                </a>
                @if ($building->rooms->count() > 0)
                    <x-action-button x-on:click="$dispatch('open-modal', 'enable-rooms-modal'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        <p>Enable Rooms</p>
                    </x-action-button>

                    <x-action-button x-on:click="$dispatch('open-modal', 'disable-rooms-modal'); dropdown = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-off-icon lucide-circle-off"><path d="m2 2 20 20"/><path d="M8.35 2.69A10 10 0 0 1 21.3 15.65"/><path d="M19.08 19.08A10 10 0 1 1 4.92 4.92"/></svg>
                        <p>Disable Rooms</p>
                    </x-action-button>    
                @endif
            </div>
        </x-actions>
    </div>

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">Rooms in this Building</h2>
            <p class="text-xs">Manage your rooms in this building here</p>
        </hgroup>

        @if ($building->rooms->count() > 0)
            <livewire:room-building-table :building="$building" />
        @else
            <div class="font-semibold text-center">
                <x-table-no-data.buildings />
            </div>
        @endif
    </div>
</div>
