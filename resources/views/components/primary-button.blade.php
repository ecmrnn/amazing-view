<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg border border-transparent hover:bg-blue-600 hover:border-blue-700 focus:border-blue-500 focus:ring-blue-500 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
