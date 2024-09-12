<div {{ $attributes->merge(['class' => 'flex items-center justify-between bg-slate-100 overflow-visible']) }}>
    <div class="flex items-center">
        <div class="m-2 rounded-full w-[30px] aspect-square grid place-items-center bg-gradient-to-r border border-blue-600 from-blue-500 to-blue-600">
            <span class="text-xs font-semibold text-white">{{ $step }}</span>
        </div>
        <h2 class="font-semibold text-md">{{ html_entity_decode($title) }}</h2>
    </div>

    <template x-if="expanded">
        <x-tooltip text="Collapse" dir="left">
            <button x-ref="content" x-on:click="expanded = ! expanded" type="button" class="grid px-2 place-items-center" x-on:click="expanded = !expanded">
                <span class="material-symbols-outlined" x-text="expanded ? 'unfold_more' : 'unfold_less'"></span>
            </button>
        </x-tooltip>
    </template>

    <template x-if="!expanded">
        <x-tooltip text="Expand" dir="left">
            <button x-ref="content" x-on:click="expanded = ! expanded" type="button" class="grid px-2 place-items-center" x-on:click="expanded = !expanded">
                <span class="material-symbols-outlined" x-text="expanded ? 'unfold_more' : 'unfold_less'"></span>
            </button>
        </x-tooltip>
    </template>
</div>