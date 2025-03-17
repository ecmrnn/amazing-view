@props([
    'type' => 'text',
    'label' => '',
])

<div class="relative z-0 w-full">
    <input 
        {{ $attributes->merge(['class' => 'block p-3 py-2.5 w-full text-xs bg-white rounded-md border border-slate-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 z-0 transition-colors ease-in-out duration-200 font-semibold invalid:border-red-500 invalid:bg-red-50 focus:invalid:border-red-500 disabled:bg-slate-100 disabled:opacity-50']) }}
        type="{{ $type }}" placeholder="{{ $label }}" />
</div>