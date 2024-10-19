<button type="button" {{ $attributes->merge(['class' => 'p-2 bg-white shadow-md border border-gray-300 text-zinc-500 rounded-md hover:scale-[.99] hover:bg-blue-500 hover:text-white hover:shadow-none disabled:opacity-25 transition-all ease-in-out duration-200 hover:border-blue-600 disabled:hover:bg-white disabled:hover:text-zinc-800 disabled:hover:border-transparent']) }}>
    {{ $slot }}
</button>