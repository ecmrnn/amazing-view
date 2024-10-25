<?php

namespace App\Livewire\App\Reservation;

use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UpdateNote extends Component
{
    use DispatchesToast;
    
    #[Validate]
    public $note = '';
    public $reservation;

    public function mount(Reservation $reservation) {
        $this->note = html_entity_decode($reservation->note);
        $this->reservation = $reservation;
    }

    public function rules() {
        return [
            'note' => Reservation::rules()['note']
        ];
    }

    public function update() {
        if ($this->reservation->note != $this->note) {
            $this->validate([
                'note' => Reservation::rules()['note']
            ]);

            $this->reservation->note = htmlentities(str_replace('"', "'", $this->note));
            $this->reservation->save();
            $this->toast('Success!', 'success', 'Yay, note updated!');
        }
    }

    public function render()
    {
        return <<<'HTML'
            <div  class="space-y-1" method="POST">
                @csrf
                @method('PATCH')

                <x-form.input-label for="note">Reservation Note</x-form.input-label>
                <x-form.textarea wire:model.live="note" name="note" rows="3" class="w-full" id="note">
                    {{ $note }}
                </x-form.textarea>

                <div class="flex items-center gap-1">
                    <x-primary-button type="button" wire:click="update()">Save Note</x-primary-button>
                    <p class="text-xs" wire:loading.delay wire:target="update()">Please wait for your amazing note</p>
                </div>
            </div>
        HTML;
    }
}
