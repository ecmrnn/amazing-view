<div {{ $attributes->merge(['class' => 'border-t border-slate-200 pt-5']) }} x-show="expanded" x-collapse.duration.1000ms>
    {{ $slot }}
</div>