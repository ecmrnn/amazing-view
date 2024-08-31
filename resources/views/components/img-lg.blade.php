@props(['src' => 'https://placehold.co/500'])

<div {{ $attributes->merge(['class' => 'rounded-lg overflow-hidden aspect-video']) }}
    style="background-image: url({{ $src }});
        background-position: center;
        background-size: cover;">
</div>