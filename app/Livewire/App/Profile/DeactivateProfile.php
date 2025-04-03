<?php

namespace App\Livewire\App\Profile;

use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeactivateProfile extends Component
{
    use DispatchesToast;

    public $user;
    public $cancel_reservation;
    #[Validate] public $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function submit() {
        $this->validate();

        $auth = new AuthService;
        
        if ($auth->validatePassword($this->password)) {
            $service = new UserService;
            $service->deactivate($this->user, $this->cancel_reservation);
            $this->redirect('/login', true);
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit="submit" x-on:profile-deactivated.window="show = false">
            <hgroup>
                <h2 class='text-lg font-semibold text-red-500'>Deactivate Account</h2>
                <p class='text-xs'>Enter your password to deactivate your account</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" wire:model.live="password" id="password" name="password" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-form.input-checkbox id="cancel_reservation" wire:model.live="cancel_reservation" label="Cancel all my pending reservations" />

            <x-loading wire:loading wire:target='submit'>Deactivating account, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Deactivate</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
