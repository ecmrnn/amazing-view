<div x-data="{ content: true }" {{ $attributes->merge(['class' => 'rounded-lg overflow-hidden border']) }}>
    {{ $slot }}
</div>