<div
    wire:key="toggle-filters-{{ $tableName }}')"
    id="toggle-filters"
    class="flex gap-3 mt-2 mr-2 sm:mt-0"
>
    <x-secondary-button
        wire:click="toggleFilters"
        type="button"
        class="flex items-center gap-1"
    >
    <div class="flex items-center gap-3 text-xs">
        <x-livewire-powergrid::icons.filter />
        <span>Filters</span>
    </div>
    </x-secondary-button>
</div>
