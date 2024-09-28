@props(['active'])

@php
$classes = 'border-b-2 font-bold border-transparent py-2 transition duration-150 ease-in-out focus:outline-none';

($active ?? false)
            ? $classes .= ' text-blue-600'
            : $classes .= ' text-zinc-800/50 hover:text-zinc-800 hover:border-blue-500 focus:text-zinc-800 focus:border-blue-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    {{ $slot }}
</a>