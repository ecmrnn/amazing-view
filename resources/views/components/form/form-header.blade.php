@props([
    'collapsable' => false,
    'title' => 'This is a default title',
])

<div {{ $attributes->merge(['class' => 'flex items-center justify-between overflow-visible']) }}>
    <h2 class="px-5 py-3 font-semibold text-md">{{ html_entity_decode($title) }}</h2>

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