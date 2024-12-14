<div {{ $attributes->merge(['class' => '']) }} x-show="expanded" x-collapse.duration.1000ms>
    {{ $slot }}
</div>