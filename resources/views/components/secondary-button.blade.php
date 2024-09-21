<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-block px-4 py-2 text-sm border border-slate-100 bg-slate-100 rounded-lg font-semibold hover:bg-slate-300/50 focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>