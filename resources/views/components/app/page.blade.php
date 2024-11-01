<a href="{{ $href }}" wire:navigate.hover>
    <x-secondary-button class="flex items-start p-3 overflow-hidden text-left bg-white border-transparent rounded-lg sm:p-5">
        <div class="space-y-3">
            <div class="p-3 text-white grid place-items-center w-full max-w-[50px] rounded-lg aspect-square  bg-gradient-to-r from-blue-500 to-blue-600">
                {{ $icon }}
            </div>
    
            <div>
                <p class="font-semibold text-md">{{ $page }}</p>
                <p class="text-xs font-normal line-clamp-2">{{ $description }}</p>
            </div>
        </div>
    </x-secondary-button>
</a>