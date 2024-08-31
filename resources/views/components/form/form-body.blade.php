<div {{ $attributes->merge(['class' => 'border-t']) }} x-show="content">
    {{ $slot }}
</div>