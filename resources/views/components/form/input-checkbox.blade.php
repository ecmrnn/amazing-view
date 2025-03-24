@props([
    'id' => '',
    'label' => '',
])

<label for="{{ $id }}" class="inline-flex items-center gap-3 select-none">
    <input {{ $attributes->merge(['class' => 'text-blue-600 border-slate-200 rounded disabled:cursor-not-allowed peer shadow-sm focus:outline-none focus:ring-0 focus:border-blue-600']) }} id="{{ $id }}" type="checkbox">
    <span class="text-xs font-semibold peer-disabled:opacity-50 peer-disabled:cursor-not-allowed">{{ $label }}</span>
</label>