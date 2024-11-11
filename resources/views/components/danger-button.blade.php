<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block text-sm px-4 py-2 bg-gradient-to-r cursor-pointer from-red-500 to-red-600 text-white shadow-[0_2px_0_0_rgb(127,29,29)] hover:translate-y-[2px] hover:shadow-[0_0_0_0_rgb(127,29,29)] font-semibold rounded-lg border border-transparent hover:border-red-700 focus:outline-none focus:ring-0 focus:border-red-600 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
