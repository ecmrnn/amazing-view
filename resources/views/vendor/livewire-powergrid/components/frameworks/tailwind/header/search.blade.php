@if (data_get($setUp, 'header.searchInput'))
    <div class="flex flex-row justify-start w-full mt-3 rounded-full md:mt-0 sm:justify-center md:justify-end">
        <div class="relative float-right w-full rounded-full sm:max-w-min group ">
            
            <x-form.input-search
                id="email"
                wire:model.live.debounce.700ms="search"
            />
            
            @if ($search)
                <span
                    class="absolute inset-y-0 right-0 flex items-center transition-all opacity-0 group-hover:opacity-100"
                >
                    <span class="p-2 rounded-full cursor-pointer focus:outline-none focus:shadow-outline">
                        <a wire:click.prevent="$set('search','')">
                            <x-livewire-powergrid::icons.x
                                class="w-4 h-4 {{ data_get($theme, 'searchBox.iconCloseClass') }}"
                                style="{{ data_get($theme, 'searchBox.iconCloseStyle') }}"
                            />
                        </a>
                    </span>
                </span>
            @endif
        </div>
    </div>
@endif
