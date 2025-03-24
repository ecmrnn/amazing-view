@props([
    'disabled' => false,
    'type' => 'text',
    'label' => '',
    'placeholder' => 'Search',
])

<div x-data="{ focus: false }"
    :class="focus ? 'border-blue-600' : 'border-slate-200'"
    class="z-0 flex items-center pl-3 text-xs text-gray-900 transition-colors duration-200 ease-in-out bg-transparent bg-white border rounded-md appearance-none border-1 focus:outline-none focus:ring-0 focus:border-blue-600 peer invalid:border-red-500 invalid:bg-red-50 focus:invalid:border-red-500">
    <label for="{{ $attributes['id'] }}" 
        :class="focus ? 'text-blue-600' : 'opacity-50'"
        class="grid w-5 transition-all duration-200 ease-in-out aspect-square place-items-center">
        <svg 
        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
    </label>
    <input {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'border-0 text-xs py-2 rounded-md focus:ring-0 font-semibold']) }} type="{{ $type }}"
    placeholder="{{ $placeholder }}"
    x-on:focus="focus = true"
    x-on:blur="focus = false"
    />
</div>