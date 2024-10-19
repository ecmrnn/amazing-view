@props([
    'currentStep' => '',
    'step' => '',
    'name' => '',
    'icon' => ''
])

@php
    // switch ($step) {
    //     case :
    //         $icon = '';
    //         break;
    // }
@endphp

<div x-data="{ currentStep: {{ $currentStep }} }">
    <div class="flex items-center gap-5">
        <div 
            @if ($step < $currentStep)
                class="w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border border-emerald-600 bg-gradient-to-r from-emerald-500/80 to-emerald-500 *:text-white"
            @elseif ($step == $currentStep)
                class="w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border bg-gradient-to-r from-blue-500 to-blue-600 border-blue-600 *:text-white"
            @else
                class="w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border *:text-zinc-800/25"
            @endif
            >
            <span>
                @if ($step >= $currentStep)
                    @if ($step == 1)
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                    @elseif ($step == 2)
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-pen"><path d="M2 21a8 8 0 0 1 10.821-7.487"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="8" r="5"/></svg>
                    @elseif ($step == 3)
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                    @endif
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
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