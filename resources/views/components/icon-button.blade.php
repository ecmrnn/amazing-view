<button type="button" {{ $attributes->merge(['class' => 'p-2 border border-transparent hover:border-slate-200 hover:bg-slate-50 rounded-md disabled:opacity-25 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>