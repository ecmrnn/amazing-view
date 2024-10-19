<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-block shadow-md px-4 py-2 text-sm border bg-white border-zinc-300/50 text-zinc-800 rounded-lg font-semibold hover:shadow-none hover:scale-[.99] focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>