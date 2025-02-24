@props(['reservation'])

<div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
    <hgroup>
        <h2 class="font-semibold">Billing Details</h2>
        <p class="text-xs">Enter the billing details below</p>
    </hgroup>

    <div class="grid gap-5 p-5 border rounded-md md:grid-cols-2 border-slate-200">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.input-group class="w-full">
                <x-form.input-label for='issue_date'>Issue Date</x-form.input-label>
                <x-form.input-date wire:model.live='issue_date' id="issue_date" name="issue_date" class="w-full" />
                <x-form.input-error field="issue_date" />
            </x-form.input-group>
            <x-form.input-group class="w-full">
                <x-form.input-label for='due_date'>Due Date</x-form.input-label>
                <x-form.input-date wire:model.live='due_date' id="due_date" name="due_date" class="w-full" />
                <x-form.input-error field="due_date" />
            </x-form.input-group>
        </div>

        <x-form.input-group>
            <x-form.input-label for='email'>Send invoice to:</x-form.input-label>
            <x-form.input-text id="email" wire:model.live='email' name="email" label="Email" />
            <x-form.input-error field="email" />
        </x-form.input-group>
    </div>

    <livewire:app.invoice.add-item />
</div>
