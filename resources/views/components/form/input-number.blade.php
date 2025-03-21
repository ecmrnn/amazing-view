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
        {{ $attributes->merge(['class' => 'peer bg-transparent py-2.5 disabled:cursor-not-allowed w-full text-xs font-semibold disabled:bg-slate-50 border-y border-x-0 border-y-slate-200 appearance-none text-center focus:outline-none focus:ring-0 focus:border-y-blue-600 invalid:bg-red-50 invalid:border-red-500 focus:invalid:border-red-500',
            'value'
        ]) }}
        x-ref="number"
        type="number" min="{{ $min }}" max="{{ $max }}" id="{{ $id }}" />

    {{-- Less Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }} - 1 >= {{ $min }} ? {{ is_null($model) ? 'number' : $number }}-- : 0; $wire.set('{{ $model }}', {{ $model }}); $dispatch('change')"
        class="w-[40px] order-first peer-focus:border-blue-600 inline-block shrink-0 rounded-s-md border border-r-0 border-slate-200 focus:outline-none focus:ring-0 peer-disabled:cursor-not-allowed peer-disabled:bg-slate-50 peer-invalid:bg-red-50 peer-invalid:border-red-500 peer-invalid:focus:border-red-500"
        type="button"
        {{ $attributes['disabled'] }}
        >-</button>

    {{-- Add Button --}}
    <button x-on:click="{{ is_null($model) ? 'number' : $number }} + 1 <= {{ $max }} ? {{ is_null($model) ? 'number' : $number }}++ : ''; $wire.set('{{ $model }}', {{ $model }}); $dispatch('change')"
        class="w-[40px] inline-block peer-focus:border-blue-600 shrink-0 rounded-e-md border border-l-0 border-slate-200 focus:outline-none focus:ring-0  peer-disabled:cursor-not-allowed peer-disabled:bg-slate-50 peer-invalid:bg-red-50 peer-invalid:border-red-500 peer-invalid:focus:border-red-500"
        type="button"
        {{ $attributes['disabled'] }}
        >+</button>
</div>