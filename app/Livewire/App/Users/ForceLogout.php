<?php

namespace App\Livewire\App\Users;

use App\Enums\SessionStatus;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForceLogout extends Component
{
    use DispatchesToast;

    #[Validate] public $password;
    public $user;
    
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

    public function forceLogout() {
        $this->validate();

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new UserService;
            $service->forceLogout($this->user);

            $this->toast('Force Logout Success!', description: 'User has been logged out!');
            $this->dispatch('force-logout-user');
            $this->reset('password');
            return;
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit="forceLogout" x-on:force-logout-user.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Force Logout</h2>
                <p class="text-xs">Logout this user from this session</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='password'>Enter your password</x-form.input-label>
                <x-form.input-text id="password" type="password" name="password" label="Password" wire:model.live="password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='forceLogout'>Logging out this user, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Force Logout</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
