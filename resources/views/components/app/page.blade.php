<a href="{{ $href }}" wire:navigate.hover class="block">
    <button class="flex items-start w-full p-5 text-left bg-white border rounded-lg border-slate-200">
        <div class="space-y-5">
            <div class="p-3 text-white grid place-items-center w-full max-w-[50px] rounded-lg aspect-square  bg-gradient-to-r from-blue-500 to-blue-600">
                {{ $icon }}
            </div>
    
            <div>
                <p class="font-semibold text-md">{{ $page }}</p>
                <p class="text-xs font-normal line-clamp-2">{{ $description }}</p>
            </div>
        </div>
    </button>
</a>