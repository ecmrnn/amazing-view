@props([
    'cols' => 30,
    'rows' => 8,
    'max' => 200,
])

<div class="space-y-2" x-data="{ max: @js($max), textarea: 'Amazing day, isn\'t it?', remaining: @js($max) }">
    <textarea {{ $attributes->merge(['class' => 'block border-1 border-slate-200 min-h-[78px] focus:border-blue-500 focus:ring-blue-500 rounded-md text-xs font-semibold border-slate-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 transition-colors ease-in-out duration-200']) }}
        maxlength="{{ $max }}"
        x-on:keyup="remaining = max - $el.value.length"
        x-init="$nextTick(() => { remaining = max - $el.value.length });"
        cols="{{ $cols }}"
        rows="{{ $rows }}">{{ $slot }}</textarea>
    <p class="text-xs text-right">Remaining Characters: <span x-text="remaining"></span> &#47; {{ $max }}</p>
</div>
