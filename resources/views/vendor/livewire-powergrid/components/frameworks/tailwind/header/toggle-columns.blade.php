@if (data_get($setUp, 'header.toggleColumns'))
    <div
        x-data="{ open: false }"
        class="mt-2 mr-2 sm:mt-0"
        @click.outside="open = false"
    >
        <x-secondary-button @click.prevent="open = ! open">
            <div class="flex items-center gap-3 text-xs">
                <x-livewire-powergrid::icons.eye-off />
                <span>Toggle Columns</span>
            </div>
        </x-secondary-button>

        <div
            x-cloak
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-10 w-56 mt-2 bg-white rounded-md shadow-lg dark:bg-pg-primary-700 ring-1 ring-black ring-opacity-5 focus:outline-none"
            tabindex="-1"
            @keydown.tab="open = false"
            @keydown.enter.prevent="open = false;"
            @keyup.space.prevent="open = false;"
        >
            <div
                role="none"
                class="p-3 space-y-1"
            >
                @foreach ($this->visibleColumns as $column)
                    <div
                        wire:click="$dispatch('pg:toggleColumn-{{ $tableName }}', { field: '{{ data_get($column, 'field') }}'})"
                        wire:key="toggle-column-{{ data_get($column, 'field') }}"
                        @class([
                            'text-zinc-800/25 ' => data_get($column, 'hidden'),
                            'cursor-pointer flex justify-between'
                        ])
                    >
                        <div>
                            {!! data_get($column, 'title') !!}
                        </div>
                        @if (!data_get($column, 'hidden'))
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
