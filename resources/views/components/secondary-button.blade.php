<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-block px-4 py-2 text-xs bg-white text-blue-800 rounded-md font-semibold hover:shadow-none focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150 disabled:shadow-none']) }}>
    {{ $slot }}
</button>