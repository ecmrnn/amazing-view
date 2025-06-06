@props([
    'options' => [],
    'selected' => null,
])

<select
    {{ $attributes->merge(['class' => 'block px-3 py-2.5 appearance-none bg-white font-semibold w-full min-w-[100px] bg-white rounded-md border border-slate-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer z-0 transition-colors ease-in-out duration-200 text-xs disabled:bg-slate-50 disabled:cursor-not-allowed']) }}
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