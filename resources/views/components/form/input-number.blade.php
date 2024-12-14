@props([
    'number' => 0,
    'min' => 0,
    'max' => 999999,
    'id' => '',
])

@php
    $model = $attributes['x-model'];
    is_null($attributes['x-model']) ?: $number = $model;
@endphp

<div 
    @class(['flex rounded-md bg-white',
        'opacity-50 bg-slate-100' => $attributes['disabled']
    ])
    x-data="{ number: @js($number) }">

    {{-- Number input --}}
    <input x-bind:value="{{ is_null($model) ? 'number' : $number }}"
        {{ $attributes->merge(['class' => 'peer bg-transparent w-full text-sm border-x-transparent border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 invalid:bg-red-50 invalid:border-red-500 focus:invalid:border-red-500',
            'value'
        ]) }}
        x-ref="number"
        type="number" min="{{ $min }}" max="{{ $max }}" id="{{ $id }}" />

    {{-- Less Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }} - 1 >= {{ $min }} ? {{ is_null($model) ? 'number' : $number }}-- : 0; $wire.set('{{ $model }}', {{ $model }}); $dispatch('change')"
        class="w-[40px] order-first inline-block shrink-0 rounded-s-md border border-r-0 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 focus:border peer-invalid:bg-red-50 peer-invalid:border-red-500 peer-invalid:focus:border-red-500"
        type="button"
        {{ $attributes['disabled'] }}
        >-</button>

    {{-- Add Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }} + 1 <= {{ $max }} ? {{ is_null($model) ? 'number' : $number }}++ : ''; $wire.set('{{ $model }}', {{ $model }}); $dispatch('change')"
        class="w-[40px] inline-block shrink-0 rounded-e-md border border-l-0 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 focus:border peer-invalid:bg-red-50 peer-invalid:border-red-500 peer-invalid:focus:border-red-500"
        type="button"
        {{ $attributes['disabled'] }}
        >+</button>
</div>