@props([
    'type' => 'text',
    'label' => '',
])

<div class="relative z-0 w-full">
    <input 
        {{ $attributes->merge(['class' => 'block px-2.5 pt-3 w-full text-sm rounded-md border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer z-0 transition-colors ease-in-out duration-200 invalid:border-red-500 invalid:bg-red-50 focus:invalid:border-red-500 disabled:bg-slate-100 disabled:opacity-50']) }}
        type="{{ $type }}" placeholder=" " />
    <label for="{{ $attributes['id'] }}" class="absolute text-sm text-zinc-800/50 duration-300 select-none transform -translate-y-4 scale-75 top-1.5 z-10 origin-[0] bg-white px-2 rounded-md hover:cursor-text peer-focus:px-2 peer-focus:text-blue-600 peer-focus: peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-1.5 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1 rtl:peer-focus:left-auto start-1 peer-invalid:text-red-500 peer-focus:peer-invalid:text-red-500">{{ $label }}</label>
</div>