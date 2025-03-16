@props([
    'status' => 999,
])

<a wire:navigate.hover {{ $attributes->merge(['class' => 'block']) }}>
    <button class="w-full p-5 text-left bg-white border rounded-lg border-slate-200">
        <div class="space-y-5">
            <div class="flex items-start justify-between w-full">
                <div class="p-3 text-white grid place-items-center w-full max-w-[50px] rounded-lg aspect-square  bg-gradient-to-r from-blue-500 to-blue-600">
                    {{ $icon }}
                </div>

                @if ($status != 999)
                    <x-status type="page" :status="$status" />
                @endif
            </div>
    
            {{ $slot }}
        </div>
    </button>
</a>