@props(['active' => false])

@php
$classes = 'border-b-2 font-semibold border-transparent py-2 transition duration-150 ease-in-out focus:outline-none relative inline-block group';

($active ?? false)
            ? $classes .= ' text-blue-600'
            : $classes .= ' text-zinc-800/80 hover:text-zinc-800 focus:text-zinc-800';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate.hover>
    {{ $slot }}

    <div 
        x-data="{ active: @js($active) }"
        x-bind:class="active ? 'w-[5px]' : 'w-0'"
        class="absolute -right-5 top-1/2 -translate-y-1/2 md:translate-y-0 md:top-full md:bottom-0 aspect-square transition-all duration-200 ease-in-out md:-translate-x-1/2 bg-blue-500 rounded-full md:left-1/2 group-hover:w-[5px]">
    </div>
</a>