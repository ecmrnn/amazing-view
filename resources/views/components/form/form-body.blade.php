<div {{ $attributes->merge(['class' => 'bg-slate-50/50 border-t']) }} x-show="expanded" x-collapse.duration.1000ms>
    {{ $slot }}
</div>