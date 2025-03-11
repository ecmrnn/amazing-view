<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-block shadow-md px-4 py-2 text-sm bg-white text-blue-800 rounded-md font-semibold hover:translate-y-[2px] hover:shadow-none focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150 disabled:translate-y-[2px] disabled:shadow-none']) }}>
    {{ $slot }}
</button>