@props(['active'])

@php
$classes = 'border border-transparent inline-flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out';

($active ?? false)
            ? $classes .= ' text-blue-500'
            : $classes .= ' hover:bg-slate-50 hover:border-slate-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
