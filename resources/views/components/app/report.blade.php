@props([
    'title' => 'Lorem ipsum',
    'description' => 'dolor sit amet.',
])

<div class="overflow-hidden bg-white border rounded-lg border-slate-200">
    <div class="flex gap-3 p-3 sm:p-5">
        <div class="p-3 text-blue-800 grid place-items-center *:mx-auto w-full max-w-[50px] rounded-full aspect-square bg-blue-50">
            {{ $icon }}
        </div>
        
        <div>
            <p class="font-semibold text-md">{{ $title }}</p>
            <p class="text-xs line-clamp-1">{{ $description }}</p>
        </div>
    </div>

    <button x-on:click="$dispatch('generate-report', { type: '{{ strtolower($title) }}' })" class="flex items-center w-full gap-2 px-5 py-3 transition-all duration-500 ease-in-out border-t hover:gap-5 group">
        <p class="text-xs font-semibold transition-all duration-200 ease-in-out text-zinc-800/50 group-hover:text-zinc-800">Create a Report</p>
        <div class="transition-all duration-500 ease-in-out opacity-0 group-hover:opacity-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </div>
    </button>
</div>