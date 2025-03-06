@props([
    'id' => '',
    'name' => '',
    'checked' => false,
    'disabled' => false,
    'reserved' => false, /* Specific for rooms */
])

@php
    $class = 'bg-white border-slate-200';
    if ($reserved) {
        $class = 'border-green-500 bg-green-200/50';
    }
    if ($disabled) {
        $class = 'border-red-500 bg-red-200/50';
    }
@endphp

<div class="relative">
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" class="hidden peer" {{ $attributes->merge() }}
        @if ($checked)
            @checked(true)
        @endif
        @if ($disabled || $reserved)
            @disabled(true)
        @endif
    >
    <label for="{{ $id }}" class="inline-flex items-center justify-between w-full border {{ $class }} rounded-lg cursor-pointer group peer-checked:border-blue-500 peer-checked:bg-blue-50/50 peer-checked:text-blue-800">
        {{ $slot }}
    </label>
</div>