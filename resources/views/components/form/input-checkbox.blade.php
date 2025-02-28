@props([
    'id' => '',
    'label' => '',
])

<label for="{{ $id }}" class="inline-flex items-center gap-3 select-none">
    <input {{ $attributes->merge(['class' => 'text-blue-600 border-gray-300 rounded shadow-sm focus:outline-none focus:ring-0 focus:border-blue-600']) }} id="{{ $id }}" type="checkbox">
    <span class="text-sm font-semibold">{{ $label }}</span>
</label>