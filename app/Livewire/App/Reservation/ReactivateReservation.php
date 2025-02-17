<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\AuthService;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

class ReactivateReservation extends Component
{
    use DispatchesToast;

    public $reservation;
    public $conflict_rooms;
    #[Validate] public $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
    }

    public function reactivate() {
        $this->validate(['password' => $this->rules()['password']]);
        
        $auth = new AuthService();
        
        if ($auth->validatePassword($this->password)) {
            $service = new ReservationService;
            $this->reservation = $service->reactivate($this->reservation);
            
            $this->toast('Success!', description:'Reservation reactivated!');
            $this->reset();
            
            $this->dispatch('reservation-reactivated');
        } else {
            $this->addError('password', 'Password mismatch, try again.');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div class="p-5 space-y-5" x-on:reservation-reactivated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Reactivate Reservation</h2>
                <p class="text-xs">Enter your password to reactivate this reservation</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password'></x-form.input-label>
                <x-form.input-text type="password" wire:model.live="password" id="password" name="password" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>
            

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click="reactivate">Reactivate</x-primary-button>
            </div>
        </div>
        HTML;
    }
}
