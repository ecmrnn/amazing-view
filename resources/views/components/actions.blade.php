<div x-data="{ dropdown: false }" class="relative">
    <x-secondary-button type="button" x-on:mouseover="dropdown = true" class="items-center hidden gap-3 text-xs sm:flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bolt"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><circle cx="12" cy="12" r="4"/></svg>
        <div>Actions</div>
    </x-secondary-button>
    
    <x-icon-button type="button" class="sm:hidden" x-on:click="dropdown = ! dropdown">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bolt"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><circle cx="12" cy="12" r="4"/></svg>
    </x-icon-button>

    {{-- Dropdown --}}
    <div x-show="dropdown" x-on:click.outside="dropdown = !dropdown" class="absolute right-0 p-3 space-y-3 translate-y-1 bg-white border rounded-md shadow-md w-max top-full border-slate-200 z-10 min-w-[200px]">
        {{ $slot }}
    </div>
</div>