<button {{ $attributes->merge(['type' => 'button', 'class' => 'px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg font-semibold hover:bg-slate-50 hover:border-slate-200 focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
