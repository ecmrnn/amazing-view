<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block shadow-[0_2px_0_0_rgba(23,37,84,1)] text-sm px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-md border border-transparent hover:translate-y-[2px] hover:shadow-[0_0_0_0_rgba(0,0,0,0.5)] hover:border-blue-700 focus:outline-none focus:ring-0 focus:border-blue-600 transition-all ease-in-out duration-200 disabled:translate-y-[2px] disabled:shadow-none disabled:cursor-not-allowed disabled:opacity-25']) }}>
    {{ $slot }}
</button>
