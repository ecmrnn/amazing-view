<div x-data="{ limit: 3, max: @entangle('max') }" class="space-y-5">
    <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
        @foreach ($featured_services as $key => $featured_service)
            <div class="space-y-5" x-transition x-data="{ key: @js($key + 1) }" x-cloak x-show="key <= limit">
                <x-img src="{{ $featured_service->image }}" />
    
                <hgroup>
                    <span class="text-xs">{{ sprintf("%02d", $key + 1) }}</span>
                    <h3 class="text-2xl font-semibold">{{ $featured_service->title }}</h3>
                </hgroup>
    
                <p class="text-justify">{{ $featured_service->description }}</p>
            </div>
        @endforeach
    </div>

    <button x-show="limit < max" type="button" class="block mx-auto text-sm font-semibold text-blue-500" x-on:click="limit = limit + 3; console.log(limit)">See more</button>
</iv>