<button type="button" {{ $attributes->merge(['class' => 'p-2 bg-white shadow-[0_2px_0_0_rgb(209,213,219)] border border-gray-300 text-zinc-500 rounded-md hover:translate-y-[2px] hover:shadow-[0_0_0_0_rgb(209,213,219)]  disabled:opacity-25 transition-all ease-in-out duration-200 disabled:translate-y-[2px] disabled:shadow-none']) }}>
    {{ $slot }}
</button>