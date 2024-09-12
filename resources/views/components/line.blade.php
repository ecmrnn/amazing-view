@props(['width' => 'w-4'])

<span {{ $attributes->merge(['class' => 'h-[1px] inline-block ' . $width]) }}></span>