<div x-data="{ expanded: true }" {{ $attributes->merge(['class' => 'border rounded-lg bg-white border-slate-200 overflow-hidden shadow-sm']) }}>
    {{ $slot }}
</div>