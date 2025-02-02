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
        $this->note = html_entity_decode($reservation->note, ENT_QUOTES, 'UTF-8');
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
            $this->toast('Success!', description: 'Yay, note updated!');
        } else {
            $this->toast('Oof, empty note!', 'warning', 'Write something on the textbox');
        }
    }

    public function render()
    {
        return <<<'HTML'
            <div  class="space-y-5" method="POST">
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
