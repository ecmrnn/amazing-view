@props(['active'])

@php
$classes = 'transition duration-150 rounded-lg ease-in-out focus:outline-none';

($active ?? false)
            ? $classes .= ' bg-slate-100'
            : $classes .= ' text-zinc-800 hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    {{ $slot }}
</a>