<div x-data="{ limit: 3, max: @entangle('max') }" class="space-y-5">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
        @foreach ($testimonials as $key => $testimonial)
            <div wire:key="{{ $testimonial->id }}"
                class="flex flex-col justify-between gap-5 p-5 border rounded-lg border-slate-200 bg-slate-50"
                x-transition x-data="{ key: @js($key + 1) }" x-cloak x-show="key <= limit">
                <div>
                    <div class="flex gap-1 mt-2">
                        @for ($rating = 0; $rating < 5; $rating++)
                            @if ($rating < $testimonial->rating)
                                <div class="p-1 text-white bg-blue-500 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun-medium-icon lucide-sun-medium"><circle cx="12" cy="12" r="4"/><path d="M12 3v1"/><path d="M12 20v1"/><path d="M3 12h1"/><path d="M20 12h1"/><path d="m18.364 5.636-.707.707"/><path d="m6.343 17.657-.707.707"/><path d="m5.636 5.636.707.707"/><path d="m17.657 17.657.707.707"/></svg>
                                </div>
                            @else
                                <div class="p-1 rounded-md text-slate-400 bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun-medium-icon lucide-sun-medium"><circle cx="12" cy="12" r="4"/><path d="M12 3v1"/><path d="M12 20v1"/><path d="M3 12h1"/><path d="M20 12h1"/><path d="m18.364 5.636-.707.707"/><path d="m6.343 17.657-.707.707"/><path d="m5.636 5.636.707.707"/><path d="m17.657 17.657.707.707"/></svg>
                                </div>
                            @endif
                        @endfor
                    </div>
                    
                    <p class="mt-5 text-sm text-justify">&quot;{!! nl2br(e($testimonial->testimonial ?? '')) !!}&quot;</p>
                </div>

                <div>
                    <p class="text-lg font-semibold text-blue-500">{{ $testimonial->name }}</p>
                    <p class="text-xs">{{ date_format(date_create($testimonial->created_at), 'F j, Y') }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <button x-show="limit < max" type="button" class="block mx-auto text-sm font-semibold text-blue-500" x-on:click="limit = limit + 3; console.log(limit)">See more</button>
</div>