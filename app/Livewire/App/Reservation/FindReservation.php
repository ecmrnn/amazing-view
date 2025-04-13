<?php

namespace App\Livewire\App\Reservation;

use App\Models\Reservation;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FindReservation extends Component
{
    #[Validate] public $rid;
    public $found = false;

    public function rules() {
        return [
            'rid' => 'required|exists:reservations,rid',
        ];
    }

    public function messages() {
        return [
            'rid.required' => 'Enter guest\'s Reservation ID',
            'rid.exists' => 'Reservation does not exist.',
        ];
    }

    public function submit() {
        $this->validate();

        $this->found = true;
        return redirect()->route('app.reservations.show', ['reservation' => $this->rid]);
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="submit" class="p-5 space-y-5">
            <hgroup>
                <h2 class='font-semibold'>Find Reservation</h2>
                <p class='text-xs'>Enter the Reservation ID of the guest</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='rid'>Reservation ID</x-form.input-label>
                <x-form.input-text id="rid" name="rid" label="RXXXXXXXXX" wire:model.live="rid" />
                <x-form.input-error field="rid" />
            </x-form.input-group>

            @if ($found)
                <hgroup class="p-5 text-green-800 border border-green-500 rounded-md bg-green-50">
                    <h2 class='font-semibold'>Reservation found!</h2>
                    <p class='text-xs'>Redirecting, please wait</p>
                </hgroup>
            @endif

            <x-loading wire:loading wire:target='submit'>Finding your reservation</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Find</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
