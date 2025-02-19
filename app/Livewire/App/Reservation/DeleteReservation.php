<?php

namespace App\Livewire\App\Reservation;

use App\Models\Reservation;
use App\Services\AuthService;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Resend;

class DeleteReservation extends Component
{
    use DispatchesToast;
    
    #[Validate] public $password = '';

    public $reservation;

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
    }

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function destroy() {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            // Delete the reservation
            $service = new ReservationService();
            $service->delete($this->reservation);
    
            $this->dispatch('reservation-deleted');
            $this->toast('Success!', description: 'Reservation deleted successfully.');
        } else {
            $this->addError('password', 'Password mismatch, try again.');
        }

    }

    public function render()
    {
        return <<<'HTML'
        <div class="p-5 space-y-5 bg-white" x-on:reservation-deleted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500 capitalize">Delete Reservation</h2>
                <p class="text-xs">You are about to delete this reservation, this action cannot be undone</p>
            </hgroup>
    
            <div class="space-y-2">
                <x-form.input-label for="password">Enter your password to delete this reservation</x-form.input-label>
                <x-form.input-text wire:model.live="password" type="password" label="Password" id="password" />
                <x-form.input-error field="password" />
            </div>
            
            <div class="flex items-center justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button type="button" wire:click="destroy">Delete</x-danger-button>
            </div>
        </div>
        HTML;
    }
}
