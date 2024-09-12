@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs']) }}>
    {{ $value ?? $slot }}
</label>