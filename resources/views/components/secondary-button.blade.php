<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-block shadow-md px-4 py-2 text-sm border bg-white border-zinc-300 text-zinc-500 rounded-lg font-semibold hover:bg-zinc-50 hover:shadow-none focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>