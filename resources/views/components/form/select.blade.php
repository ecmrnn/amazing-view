@props([
    'options' => [],
    'selected' => null,
])

<select
    {{ $attributes->merge(['class' => 'block px-2.5 appearance-none w-full min-w-[100px] text-gray-900 bg-white rounded-md border border-slate-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer z-0 transition-colors ease-in-out duration-200 text-sm disabled:opacity-50 disabled:bg-slate-100']) }}
    >

    @forelse ($options as $key => $option)
        <option
            value="{{ $key }}"
            @if ($selected == $key)
                @selected(true)
            @endif
        >
            {{ $option }}
        </option>
    @empty
        {{ $slot }}
    @endforelse
</select>