@props([
    'id' => '',
    'name' => '',
    'checked' => false,
])

<div class="relative">
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" class="hidden peer" {{ $attributes->merge() }}
        @if ($checked)
            @checked(true)
        @endif
    >
    <label for="{{ $id }}" class="inline-flex items-center justify-between w-full px-3 py-2 bg-white border rounded-lg cursor-pointer group border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50/50">
        {{ $slot }}
    </label>
</div>