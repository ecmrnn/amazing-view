<select {{ $attributes->merge(['class' => 'block px-2.5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer z-0 transition-colors ease-in-out duration-200']) }}>
    {{ $slot }}
</select>