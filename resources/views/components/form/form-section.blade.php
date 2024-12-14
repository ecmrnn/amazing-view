<div x-data="{ expanded: true }" {{ $attributes->merge(['class' => 'border rounded-lg bg-slate-50 border-slate-200 overflow-hidden shadow-sm']) }}>
    {{ $slot }}
</div>