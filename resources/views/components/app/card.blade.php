@props([
    'data' => 0,
    'label' => 'Lorem ipsum',
    'href' => 'dashboard',
    'hasLink' => true,
])

<div class="overflow-hidden bg-white rounded-lg">
    <div class="flex gap-3 p-3 sm:p-5">
        <div class="p-3 text-white grid place-items-center *:mx-auto w-full max-w-[50px] rounded-md aspect-square bg-gradient-to-r from-blue-500 to-blue-600">
            {{ $icon }}
        </div>
        
        <div>
            <p class="text-xl font-semibold">{{ $data }}</p>
            <p class="text-xs line-clamp-1">{{ $label }}</p>
        </div>
    </div>

    @if ($hasLink)
        <a href="{{ route($href) }}" class="flex items-center gap-2 px-5 py-3 transition-all duration-500 ease-in-out border-t hover:gap-5 group" wire:navigate>
            <p class="text-xs font-semibold transition-all duration-200 ease-in-out text-zinc-800/50 group-hover:text-zinc-800">View in detail</p>
            <div class="transition-all duration-500 ease-in-out opacity-0 group-hover:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </div>
        </a>
    @endif
</div>