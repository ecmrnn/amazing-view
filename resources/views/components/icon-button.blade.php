<button type="button" {{ $attributes->merge(['class' => 'p-2 bg-white shadow-md border border-gray-300 text-zinc-500 rounded-md hover:translate-y-[2px] hover:shadow-none  disabled:opacity-25 transition-all ease-in-out duration-200 disabled:translate-y-[2px] disabled:shadow-none']) }}>
    {{ $slot }}
</button>