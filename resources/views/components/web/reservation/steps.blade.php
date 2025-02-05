@props([
    'currentStep' => '',
    'step' => '',
    'name' => '',
    'icon' => ''
])

<button type="button" class="block w-full text-left" x-data="{ currentStep: {{ $currentStep }} }" @disabled($step >= $currentStep || $currentStep >= 3) wire:click="goToStep('{{ $step }}')">
    <div class="flex flex-col">
        {{-- <div @class([
            'w-full h-1',
            'bg-gradient-to-r from-blue-500/80 to-blue-500' => $step == $currentStep,
            'bg-gradient-to-r from-emerald-500/80 to-emerald-500' => $step < $currentStep,
            'bg-slate-200' => $step > $currentStep,
            ])>
        </div> --}}

        <hgroup @class(['py-3 px-4 border rounded-lg',
            'bg-blue-200/50 border-blue-500 text-blue-600' => $step == $currentStep,
            'bg-emerald-200/50 border-emerald-500 text-emerald-600' => $step < $currentStep,
            'border-slate-200 opacity-50' => $step > $currentStep
            ])>
            <p @class(['text-xs uppercase'])>Step {{ $step }}</p>
            <p class="font-semibold">{{ $name }}</p>
        </hgroup>
    </div>
</button>