<div x-data="{ expanded: true }" {{ $attributes->merge(['class' => 'rounded-lg overflow-hidden border']) }}>
    {{ $slot }}
</div>