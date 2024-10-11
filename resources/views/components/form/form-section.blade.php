<div x-data="{ expanded: true }" {{ $attributes->merge(['class' => 'rounded-lg border overflow-hidden']) }}>
    {{ $slot }}
</div>