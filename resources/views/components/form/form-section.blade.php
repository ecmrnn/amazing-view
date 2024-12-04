<div x-data="{ expanded: true }" {{ $attributes->merge(['class' => 'border rounded-lg overflow-hidden shadow-sm']) }}>
    {{ $slot }}
</div>