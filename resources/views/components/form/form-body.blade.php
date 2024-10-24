<div {{ $attributes->merge(['class' => 'bg-slate-50/50 border-gray-300 border-t']) }} x-show="expanded" x-collapse.duration.1000ms>
    {{ $slot }}
</div>