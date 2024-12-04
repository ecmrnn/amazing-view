<div {{ $attributes->merge(['class' => 'bg-white']) }} x-show="expanded" x-collapse.duration.1000ms>
    {{ $slot }}
</div>