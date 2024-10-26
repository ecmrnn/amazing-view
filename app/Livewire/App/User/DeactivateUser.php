<?php

namespace App\Livewire\App\User;

use App\Models\User;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class DeactivateUser extends Component
{
    use DispatchesToast;
    
    public $user;
    public $password_deactivate;

    public function rules() {
        return User::rules();
    }

    public function messages() {
        return User::messages();
    }
    
    public function validationAttributes() {
        return User::validationAttributes();
    }

    public function deactivateUser() {
        $this->validate([
            'password_deactivate' => 'required',
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password_deactivate, $admin->password)) {
            // deactivate user
            $user = User::query()->find($this->user->id)->update([
                'status' => User::STATUS_INACTIVE
            ]);

            if ($user) {
                $this->toast('User Deactivated', 'success', 'User successfully deactivated!');
                $this->dispatch('user-deactivated');
                // reset
                $this->reset('password_deactivate');
            }
        }       
    }

    public function mount(User $user) {
        $this->user = $user;
    }
    public function render()
    {
        return <<<'HTML'
        <section class="p-5 space-y-5 bg-white" x-on:user-deactivated.window="show = false">
            <hgroup>
                <h2 class="font-semibold text-center text-red-500 capitalize">Deactivate User</h2>
                <p class="max-w-sm text-sm text-center">Are you sure you really want to deactivate <strong class="text-blue-500">{{ ucwords($user->first_name) }}</strong>?</p>
            </hgroup>

            <div class="space-y-2">
                <p class="text-xs">Enter your password to deactivate this user.</p>
                <x-form.input-text wire:model="password_deactivate" type="password" label="Password" id="password-{{ $user->id }}" />
                <x-form.input-error field="password_deactivate" />
            </div>
            
            <div class="flex items-center justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                <x-danger-button type="button" wire:click='deactivateUser'>Yes, Deactivate</x-danger-button>
            </div>
        </section>
        HTML;
    }
}
