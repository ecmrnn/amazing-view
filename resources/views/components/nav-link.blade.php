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
        class="absolute hidden w-5 h-1 transition-all duration-200 ease-in-out -translate-y-1/2 bg-blue-500 rounded-full -right-5 top-1/2 md:translate-y-0 md:top-full md:bottom-0 md:-translate-x-1/2 md:left-1/2 md:block">
    </div>
</a>