@props([
    'currentStep' => '',
    'step' => '',
    'name' => '',
    'icon' => ''
])

<button type="button" class="block w-full text-left" x-data="{ currentStep: {{ $currentStep }} }" @disabled($step >= $currentStep) wire:click="goToStep('{{ $step }}')">
    <div class="flex flex-col">
        <hgroup @class(['py-3 px-4 border rounded-lg',
            'bg-blue-50 border-blue-500 text-blue-800' => $step == $currentStep,
            'bg-emerald-50 border-emerald-500 text-emerald-800' => $step < $currentStep,
            'border-slate-200 opacity-50' => $step > $currentStep
            ])>
            <p @class(['text-xs uppercase'])>Step {{ $step }}</p>
            <p class="font-semibold">{{ $name }}</p>
        </hgroup>
    </div>
</button>