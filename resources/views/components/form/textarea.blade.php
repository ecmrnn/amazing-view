@props([
    'cols' => 30,
    'rows' => 8,
])

<textarea {{ $attributes->merge(['class' => 'block border-1 border-slate-200 min-h-[78px] focus:border-blue-500 focus:ring-blue-500 rounded-md text-sm border-slate-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 transition-colors ease-in-out duration-200']) }}
    cols="{{ $cols }}"
    rows="{{ $rows }}">{{ $slot }}</textarea>