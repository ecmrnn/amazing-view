<?php

namespace App\Livewire\App\Guest;

use App\Models\Reservation;
use App\Models\User;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SearchGuest extends Component
{
    use DispatchesToast;

    #[Validate] public $email;

    public function rules() {
        return [
            'email' => 'required|email',
        ];
    }

    public function find() {
        $this->validate(['email' => $this->rules()['email']]);

        // Find the guest's info
        $guest_details = User::select('first_name', 'last_name', 'phone', 'email', 'address')->where('email', $this->email)->first();

        if (!empty($guest_details)) {
            $this->dispatch('guest-found', $guest_details);
            $this->toast('Success', description: 'Guest found, details fetched!');
            $this->reset();
        } else {
            $this->toast('No guest found', 'warning', 'Guest not found!');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit='find' class="p-5 space-y-5" x-on:guest-found.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Search Guest</h2>
                <p class="text-xs">Enter the guest&apos;s email address to search for their details.</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='search-email'>Email Address</x-form.input-label>
                <x-form.input-text id="search-email" wire:model.live="email" name="search-email" label="Email" />
                <x-form.input-error field="email" />
            </x-form.input-group>

            <x-loading wire:loading wire:target="find">Please wait while we find this guest...</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit">Find</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
