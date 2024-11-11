@props([
    'collapsable' => true,
    'step' => 1,
    'title' => 'This is a default title',
])

<div {{ $attributes->merge(['class' => 'flex items-center justify-between bg-white overflow-visible']) }}>
    <div class="flex items-center">
        {{-- <div class="m-2 rounded-md w-[30px] aspect-square grid place-items-center bg-gradient-to-r border border-blue-600 from-blue-500 to-blue-600">
            <span class="text-xs font-semibold text-white">{{ $step }}</span>
        </div> --}}
        <div class="m-2 w-[30px] aspect-square grid place-items-center">
            <span class="text-sm font-semibold text-zinc-800/50">{{ $step }}</span>
        </div>
        <h2 class="text-sm font-semibold">{{ html_entity_decode($title) }}</h2>
    </div>

    @if ($collapsable)
        <template x-if="expanded">
            <x-tooltip text="Collapse" dir="left">
                <button x-ref="content" x-on:click="expanded = ! expanded" type="button" class="grid p-4 place-items-center" x-on:click="expanded = !expanded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up"><path d="m18 15-6-6-6 6"/></svg>
                </button>
            </x-tooltip>
        </template>

        <template x-if="!expanded">
            <x-tooltip text="Expand" dir="left">
                <button x-ref="content" x-on:click="expanded = ! expanded" type="button" class="grid p-4 place-items-center" x-on:click="expanded = !expanded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
                </button>
            </x-tooltip>
        </template>
    @endif
</div>