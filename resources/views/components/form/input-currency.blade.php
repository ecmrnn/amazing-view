@props([
    'number' => 0,
    'min' => 0,
    'id' => null,
])

@php
    $model = $attributes['x-model'];
    is_null($attributes['x-model']) ? $number = 0 : $number = $model;
@endphp

<div
    @if (is_null($model))
        x-data="{ number: 0 }"
    @endif>

    <div class="relative">
        <div class="absolute -translate-y-1/2 select-none top-1/2 left-3 text-zinc-800/25">
            <x-currency />
        </div>

        {{-- Number input --}}
        <input
            {{ $attributes->merge(['class' => 'pl-8 bg-white peer transition-colors ease-in-out duration-200 w-full text-xs font-semibold rounded-md border-slate-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 invalid:bg-red-50 invalid:border-red-500 focus:invalid:border-red-500 disabled:bg-slate-50 disabled:hover:cursor-not-allowed']) }}
            type="number" min="{{ $min }}" id="{{ $id }}" />
    </div>
</div>