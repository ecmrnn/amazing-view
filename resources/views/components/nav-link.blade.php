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
        x-bind:class="active ? 'w-1/3' : 'w-0'"
        class="absolute hidden h-1 transition-all duration-200 ease-in-out -translate-y-1/2 bg-blue-500 rounded-full -right-5 top-1/2 md:translate-y-0 md:top-full md:bottom-0 md:-translate-x-1/2 md:left-1/2 md:block md:group-hover:w-1/2">
    </div>
</a>