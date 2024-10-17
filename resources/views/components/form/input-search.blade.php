@props([
    'disabled' => false,
    'type' => 'text',
    'label' => '',
    'placeholder' => 'Search',
])

<div class="z-0 flex items-center pl-3 text-sm text-gray-900 transition-colors duration-200 ease-in-out bg-transparent bg-white border border-gray-300 rounded-lg appearance-none border-1 focus:outline-none focus:ring-0 focus:border-blue-600 peer invalid:border-red-500 invalid:bg-red-50 focus:invalid:border-red-500">
    <label for="{{ $attributes['id'] }}" class="grid w-5 opacity-50 aspect-square place-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
    </label>
    <input {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'border-0 text-sm rounded-lg focus:ring-0']) }} type="{{ $type }}" placeholder="{{ $placeholder }}" />
</div>