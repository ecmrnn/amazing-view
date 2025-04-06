<?php

namespace App\Livewire\App\Promo;

use App\Services\PromoService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePromo extends Component
{
    use DispatchesToast;

    #[Validate] public $name;
    #[Validate] public $code;
    #[Validate] public $amount;
    #[Validate] public $start_date;
    #[Validate] public $end_date;
    public $min_date;

    public function rules() {
        return [
            'name' => 'required|max:255|string|regex:/^[A-Za-z0-9\- ]+$/',
            'code' => 'required|unique:promos,code|alpha_num:ascii',
            'amount' => 'required|integer',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function submit() {
        $validated = $this->validate();

        $service = new PromoService;
        $promo = $service->create($validated);

        if ($promo) {
            $this->toast('Success', description: 'Success promo created!');
            $this->dispatch('promo-created');
            $this->dispatch('pg:eventRefresh-PromoTable');
            $this->reset();
            return;
        }
    }

    public function render()
    {
        $this->min_date = now()->format('Y-m-d');
        return <<<'HTML'
        <x-modal.full name='add-promo-modal' maxWidth='sm'>
            <form x-data="{ amount: @entangle('amount') }" class="p-5 space-y-5" wire:submit="submit" x-on:promo-created.window="show = false">
                <hgroup>
                    <h2 class='text-lg font-semibold'>Create Promo</h2>
                    <p class='text-xs'>Enter promo details here</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.input-label for='name'>Promo Name</x-form.input-label>
                    <x-form.input-text id="name" name="name" label="Promo name" wire:model.live="name" />
                    <x-form.input-error field="name" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='code'>Promo Code</x-form.input-label>
                    <x-form.input-text id="code" name="code" class="uppercase" label="AMAZING!" wire:model.live="code" />
                    <x-form.input-error field="code" />
                </x-form.input-group>

                <x-form.input-group>
                    <x-form.input-label for='amount'>Discount Amount (Fixed)</x-form.input-label>
                    <x-form.input-currency id="amount" name="amount" x-model="amount" />
                    <x-form.input-error field="amount" />
                </x-form.input-group>

                <div class="grid grid-cols-2 gap-5">
                    <x-form.input-group>
                        <x-form.input-label for='start_date'>Promo Starts</x-form.input-label>
                        <x-form.input-date id="start_date" name="start_date" class="w-full" wire:model.live="start_date" min="{{ $min_date }}" />
                        <x-form.input-error field="start_date" />
                    </x-form.input-group>

                    <x-form.input-group>
                        <x-form.input-label for='end_date'>Promo Ends</x-form.input-label>
                        <x-form.input-date id="end_date" name="end_date" class="w-full" wire:model.live="end_date" min="{{ $start_date }}" />
                        <x-form.input-error field="end_date" />
                    </x-form.input-group>
                </div>

                <x-loading wire:loading wire:target='submit'>Creating promo, please wait</x-loading>

                <div class="flex justify-end gap-1">
                    <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button>Create</x-primary-button>
                </div>
            </form>
        </x-modal.full>
        HTML;
    }
}
