@props([
    'currentStep' => '',
    'step' => '',
    'name' => '',
    'icon' => ''
])

<div x-data="{ currentStep: {{ $currentStep }} }">
    <div class="flex items-center gap-5">
        <div 
            @if ($step <= $currentStep)
                class="w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border bg-gradient-to-r from-blue-500 to-blue-600 border-blue-600 *:text-white"
            @else
                class="w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border *:text-zinc-800/25"
            @endif
            >
            <span class="material-symbols-outlined">
                @if ($step >= $currentStep)
                    {{ $icon }}
                @else
                    check
                @endif
            </span>
        </div>

        <span
            @if ($step <= $currentStep)
                class="text-2xl font-semibold"
            @else
                class="text-2xl font-semibold text-zinc-800/25"
            @endif
            >
            {{ $step }}
        </span>
        <p 
            @if ($step <= $currentStep)
                class="text-xs"
            @else
                class="text-xs text-zinc-800/50"
            @endif
            >
            {{ $name }}
        </p>
    </div>
</div>