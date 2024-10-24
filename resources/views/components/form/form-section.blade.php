<div x-data="{ expanded: true }" {{ $attributes->merge(['class' => 'rounded-lg border border-gray-300 overflow-hidden']) }}>
    {{ $slot }}
</div>