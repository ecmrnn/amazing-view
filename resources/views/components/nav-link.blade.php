@props(['active' => false])

@php
$classes = 'border-b-2 font-semibold border-transparent py-2 transition duration-150 ease-in-out focus:outline-none relative inline-block group';

($active ?? false)
            ? $classes .= ' text-blue-600'
            : $classes .= ' text-zinc-800/80 hover:text-blue-600 focus:text-blue-600';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    {{ $slot }}

    <div 
        x-data="{ active: @js($active) }"
        x-show="active" x-cloak
        class="absolute text-blue-500 -translate-x-1/2 left-1/2"
        {{-- class="absolute hidden w-5 h-1 transition-all duration-200 ease-in-out -translate-y-1/2 bg-blue-500 rounded-full -right-5 top-1/2 md:translate-y-0 md:top-full md:bottom-0 md:-translate-x-1/2 md:left-1/2 md:block" --}}>
        <svg class="fill-blue-500" width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" id="wave" class="icon glyph"><path d="M16.5,14a4.06,4.06,0,0,1-2.92-1.25,2,2,0,0,0-3.17,0,4,4,0,0,1-5.83,0A2.1,2.1,0,0,0,3,12a1,1,0,0,1,0-2,4,4,0,0,1,2.91,1.25,2,2,0,0,0,3.17,0,4,4,0,0,1,5.83,0,2,2,0,0,0,3.17,0A4.06,4.06,0,0,1,21,10a1,1,0,0,1,0,2,2.12,2.12,0,0,0-1.59.75A4,4,0,0,1,16.5,14Z"></path></svg>
    </div>
</a>