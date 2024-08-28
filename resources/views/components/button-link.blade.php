<a {{ $attributes->merge(['class' => 'inline-block px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg border border-transparent hover:shadow-lg hover:border-blue-700 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</a>