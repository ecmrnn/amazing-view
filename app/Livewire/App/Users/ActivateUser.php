<?php

namespace App\Livewire\App\Users;

use App\Enums\UserStatus;
use App\Models\User;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ActivateUser extends Component
{
    use DispatchesToast;
    
    public $user;
    public $password_activate;

    public function rules() {
        return User::rules();
    }

    public function messages() {
        return User::messages();
    }
    
    public function validationAttributes() {
        return User::validationAttributes();
    }

    public function activateUser() {
        $this->validate([
            'password_activate' => 'required',
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password_activate, $admin->password)) {
            $user = User::withTrashed()->find($this->user->id);

            if ($user) {
                $service = new UserService;
                $service->activate($user);
                $this->toast('User Activated', 'success', 'User successfully activated!');
                $this->dispatch('user-activated');
                // reset
                $this->reset('password_activate');
            }
        }       
    }

    public function mount(User $user) {
        $this->user = $user;
    }
    public function render()
    {
        return <<<'HTML'
        <form wire:submit="activateUser" class="p-5 space-y-5" x-on:user-activated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Activate User</h2>
                <p class="text-xs">This user is about to gain access to your system.</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="password-activate-{{ $user->id }}">Enter your password</x-form.input-label>
                <x-form.input-text wire:model="password_activate" type="password" label="Password" id="password-activate-{{ $user->id }}" />
                <x-form.input-error field="password_activate" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='activateUser'>Activating user, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Activate</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
