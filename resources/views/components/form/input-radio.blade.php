@props([
    'id' => '',
    'label' => '',
])

<label for="{{ $id }}" class="inline-flex items-center gap-3 select-none">
    <input type="radio" {{ $attributes->merge(['class' => 'text-blue-600 border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-0 focus:border-blue-600']) }} id="{{ $id }}">
    <span class="text-xs font-semibold">{{ $label }}</span>
</label>