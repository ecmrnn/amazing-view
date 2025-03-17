<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block text-xs px-4 py-2 bg-gradient-to-r cursor-pointer from-red-500 to-red-600 text-white shadow-md hover:translate-y-[2px] hover:shadow-none font-semibold rounded-lg border border-transparent hover:border-red-700 focus:outline-none focus:ring-0 focus:border-red-600 disabled:cursor-not-allowed disabled:translate-y-[2px] disabled:shadow-none disabled:opacity-25 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
