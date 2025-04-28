@props(['srcs' => null])

<div x-data="{
        key: 0,
        prev() {
            if (this.key - 1 < 0) {
                this.key = @js(count($srcs) - 1);
                return;
            }

            this.key = this.key - 1;
        },
        next() {
            if (this.key + 1 > @js(count($srcs) - 1)) {
                this.key = 0;
                return;
            }

            this.key = this.key + 1;
        }
    }"
    class="space-y-5">
    <div class="relative overflow-hidden aspect-video">
        @foreach ($srcs as $key => $src)
            <div x-show="key == @js($key)" 
                x-transition
                x-transition.origin.center
                x-transition:leave.scale.105
                >
                <x-img src="{{ $src }}" class="absolute" />
            </div>
        @endforeach
    </div>

    <div class="flex justify-between w-full">
        <x-tooltip text="Previous">
            <x-icon-button type="button" x-ref="content" x-on:click="prev()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </x-icon-button>
        </x-tooltip>

        <div class="flex items-center gap-1">
            @for ($index = 0; $index < count($srcs); $index++)
                <button x-on:click="key = @js($index)" x-bind:class="@js($index) != key 
                ? 'w-2 rounded-full bg-slate-200 aspect-square'
                : 'w-2 rounded-full bg-blue-500 aspect-square'"></button>
            @endfor
        </div>

        <x-tooltip text="Next">
            <x-icon-button type="button" x-ref="content" x-on:click="next()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </x-icon-button>
        </x-tooltip>
    </div>
</div>