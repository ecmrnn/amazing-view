<button type="button" {{ $attributes->merge(['class' => 'flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50']) }}>
    {{ $slot }}
</button>