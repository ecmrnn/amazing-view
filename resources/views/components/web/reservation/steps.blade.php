@props([
    'step' => '',
    'name' => '',
    'currentStep' => '',
    'icon' => ''
])

@php
    $iconClass = "w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border ";
    $spanClass = "font-semibold text-2xl text-zinc-800/25";
    $pClass = "text-xs text-zinc-800/50";

    if ($step <= $currentStep) {
        $iconClass .= "bg-blue-500 border-blue-600 *:text-white";
        $spanClass = "font-semibold text-2xl";
        $pClass = "text-xs";
    }
@endphp

<div>
    <div class="flex gap-5 items-center">
        <div class="{{ $iconClass }}">
            <span class="material-symbols-outlined">{{ $icon }}</span>
        </div>
        <span class="{{ $spanClass }}">{{ $step }}</span>
        <p class="{{ $pClass }}">{{ $name }}</p>
    </div>
</div>