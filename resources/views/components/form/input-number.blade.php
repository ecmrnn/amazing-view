@props([
    'number' => 0,
    'min' => 0
])

@php
    $model = $attributes['x-model'];
    is_null($attributes['x-model']) ? $number = 0 : $number = $model;
@endphp

<div class="flex" 
    @if (is_null($model))
        x-data="{ number: 0 }"
    @endif>

    {{-- Less Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }} - 1 >= {{ $min }} ? {{ is_null($model) ? 'number' : $number }}-- : 0"
        class="w-[40px] inline-block shrink-0 rounded-s-lg border border-r-0 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 focus:border"
        type="button">-</button>

    {{-- Number input --}}
    <input x-bind:value="{{ is_null($model) ? 'number' : $number }}"
        {{ $attributes->merge(['class' => 'appearance-none w-full border-gray-300 text-sm focus:outline-none focus:ring-0 focus:border-blue-600']) }}
        type="number" min="{{ $min }}"  />

    {{-- Add Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }}++"
        class="w-[40px] inline-block shrink-0 rounded-e-lg border border-l-0 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 focus:border"
        type="button">+</button>
</div>