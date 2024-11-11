@props([
    'src' => 'https://placehold.co/400',
    'position' => 'center',
])

<div {{ $attributes->merge(['class' => 'rounded-lg overflow-hidden aspect-video']) }}
    style="background-image: url({{ $src }});
        background-position: {{ $position }};
        background-size: cover;">
</div>