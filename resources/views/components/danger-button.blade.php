<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block text-sm px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg border border-transparent hover:shadow-lg hover:border-red-700 focus:outline-none focus:ring-0 focus:border-red-600 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
