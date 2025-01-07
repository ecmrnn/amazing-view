@props(['active'])

@php
$classes = 'transition duration-150 rounded-md ease-in-out focus:outline-none';

($active ?? false)
            ? $classes .= ' text-blue-500 bg-blue-50'
            : $classes .= ' hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500 ';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    {{ $slot }}
</a>