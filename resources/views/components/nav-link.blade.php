@props(['active'])

@php
$classes = 'border-b-2 border-transparent inline-flex items-center py-2 transition duration-150 ease-in-out';

($active ?? false)
            ? $classes .= ' text-zinc-800'
            : $classes .= ' text-zinc-800/50 hover:text-zinc-800 hover:border-blue-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
