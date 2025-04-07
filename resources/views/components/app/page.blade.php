@props([
    'status' => 999,
])

<a wire:navigate.hover {{ $attributes->merge(['class' => 'block']) }}>
    <button class="w-full p-5 text-left bg-white border rounded-lg border-slate-200">
        <div class="space-y-5">
            <div class="flex items-start justify-between w-full">
                <div class="p-3 text-blue-800 grid place-items-center *:mx-auto w-full max-w-[50px] rounded-full aspect-square bg-blue-50">
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