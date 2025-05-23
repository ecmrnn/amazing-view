<section x-data="{ grid_view: true }" class="max-w-screen-lg p-5 mx-auto space-y-5 bg-white border rounded-lg border-slate-200">
    <div class="flex items-start justify-between gap-5">
        <hgroup>
            <h2 class="font-semibold">Buildings</h2>
            <p class="text-xs">View your established buildings here</p>
        </hgroup>

        <div>
            <x-tooltip text="Toggle View" dir="top">
                <x-icon-button x-ref="content" x-cloak x-on:click="grid_view = !grid_view">
                    <svg x-show="grid_view" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    <svg x-show="!grid_view" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rows-3"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M21 9H3"/><path d="M21 15H3"/></svg>
                </x-icon-button>
            </x-tooltip>
        </div>
    </div>

    <div x-show="grid_view" class="grid gap-5 lg:grid-cols-3 sm:grid-cols-2">
        @foreach ($buildings as $building)
            <div wire:key="{{ $building->id }}" x-data="{ rooms_count: @js($building->rooms->count()) }" class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                <div class="flex flex-col justify-between h-full gap-5">
                    <div class="space-y-5">
                        <x-img src="{{ $building->image }}" />

                        <div>
                            <h2 class="text-lg font-semibold">{{ $building->name }}</h2>
                            <p class="text-sm line-clamp-3">{{ $building->description }}</p>
                            <p class="mt-5 text-sm"><strong>Prefix</strong>: {{ $building->prefix }}</p>
                            @if ($building->rooms->count())
                                <p class="text-sm"><strong>Rooms</strong>: {{ $building->rooms_count }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('app.buildings.show', ['building' => $building->id]) }}" wire:navigate>
                            <x-primary-button>View Rooms</x-primary-button>
                        </a>

                        <div class="flex gap-1">
                            <a href="{{ route('app.buildings.edit', ['building' => $building->id]) }}" wire:navigate>
                                <x-tooltip text="Edit" dir="bottom">
                                    <x-icon-button x-ref="content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" /><path d="m15 5 4 4" /></svg>
                                    </x-icon-button>
                                </x-tooltip>
                            </a>

                            <x-tooltip text="Delete" dir="bottom">
                                <x-icon-button x-ref="content" x-bind:disabled="rooms_count > 0" x-on:click="$dispatch('open-modal', 'delete-building-{{ $building->id }}-modal')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                </x-icon-button>
                            </x-tooltip>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <x-modal.full name='delete-building-{{ $building->id }}-modal' maxWidth='sm'>
                    <livewire:app.buildings.delete-building wire:key="delete-{{ $building->id }}" :building="$building" />
                </x-modal.full>
            </div>
        @endforeach
    </div>

    <div x-show="!grid_view">
        <livewire:tables.building-table />
    </div>
</section>