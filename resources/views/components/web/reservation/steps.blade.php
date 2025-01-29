@props([
    'currentStep' => '',
    'step' => '',
    'name' => '',
    'icon' => ''
])

<button type="button" class="block w-full text-left" x-data="{ currentStep: {{ $currentStep }} }" @disabled($step >= $currentStep || $currentStep >= 3) wire:click="goToStep('{{ $step }}')">
    <div class="flex flex-col">
        <div @class([
            'w-full h-1',
            'bg-gradient-to-r from-blue-500/80 to-blue-500' => $step == $currentStep,
            'bg-gradient-to-r from-emerald-500/80 to-emerald-500' => $step < $currentStep,
            'bg-slate-200' => $step > $currentStep,
            ])>
        </div>

        <hgroup @class(['py-3 px-4',
            'bg-gradient-to-b from-blue-500/20 to-blue-500/0' => $step == $currentStep,
            'bg-gradient-to-b from-emerald-500/20 to-emerald-500/0' => $step < $currentStep,
            ])>
            <p @class(['text-xs uppercase', 'opacity-50' => $step > $currentStep])>Step {{ $step }}</p>
            <p class="font-semibold">{{ $name }}</p>
        </hgroup>
    </div>
</button>