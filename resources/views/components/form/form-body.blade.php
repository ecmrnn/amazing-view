<div {{ $attributes->merge(['class' => 'bg-white border-gray-300 border-t border-dashed']) }} x-show="expanded" x-collapse.duration.1000ms>
    {{ $slot }}
</div>