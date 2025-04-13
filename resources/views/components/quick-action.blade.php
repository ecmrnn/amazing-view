<a wire:navigate {{ $attributes->merge(['class' => 'inline-flex items-center justify-between p-5 text-white rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 group hover:cursor-pointer']) }}>
    {{ $slot }}

    <div class="transition-all duration-200 ease-in-out -translate-x-full opacity-0 group-hover:opacity-100 group-hover:translate-x-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </div>
</a>