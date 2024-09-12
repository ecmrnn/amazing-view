@props([
    'step' => '',
    'name' => '',
    'icon' => ''
])

<div x-data="{ currentStep: $store.step.count }" x-effect="currentStep = $store.step.count">
    <div class="flex items-center gap-5">
        <div x-bind:class="({{ $step }} <= currentStep) ? 'w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border bg-gradient-to-r from-blue-500 to-blue-600 border-blue-600 *:text-white' : 'w-[45px] shrink-0 aspect-square grid place-items-center rounded-md border *:text-zinc-800/25'">
            <span x-text="({{ $step }} >= currentStep) ? '{{ $icon }}' : 'check'" class="material-symbols-outlined"></span>
        </div>
        <span x-bind:class="({{ $step }} <= currentStep) ? 'font-semibold text-2xl' : 'font-semibold text-2xl text-zinc-800/25'">{{ $step }}</span>
        <p x-bind:class="({{ $step }}) <= currentStep ? 'text-xs' : 'text-xs text-zinc-800/50'">{{ $name }}</p>
    </div>
</div>