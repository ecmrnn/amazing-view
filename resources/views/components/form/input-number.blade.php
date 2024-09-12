@props([
    'number' => 0,
    'min' => 0,
    'id' => ''
])

@php
    $model = $attributes['x-model'];
    is_null($attributes['x-model']) ? $number = 0 : $number = $model;
@endphp

<div class="flex bg-white rounded-lg" 
    @if (is_null($model))
        x-data="{ number: 0 }"
    @endif>
    
    {{-- Number input --}}
    <input x-bind:value="{{ is_null($model) ? 'number' : $number }}"
        {{ $attributes->merge(['class' => 'peer w-full text-sm border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 invalid:bg-red-50 invalid:border-red-500 focus:invalid:border-red-500']) }}
        type="number" min="{{ $min }}" />

    {{-- Less Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }} - 1 >= {{ $min }} ? {{ is_null($model) ? 'number' : $number }}-- : 0; $wire.set('{{ $model }}', {{ $model }})"
        class="w-[40px]  order-first inline-block shrink-0 rounded-s-lg border border-r-0 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 focus:border peer-invalid:bg-red-50 peer-invalid:border-red-500 peer-invalid:focus:border-red-500"
        type="button">-</button>

    {{-- Add Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }}++; $wire.set('{{ $model }}', {{ $model }})"
        class="w-[40px] inline-block shrink-0 rounded-e-lg border border-l-0 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 focus:border peer-invalid:bg-red-50 peer-invalid:border-red-500 peer-invalid:focus:border-red-500"
        type="button">+</button>
</div>