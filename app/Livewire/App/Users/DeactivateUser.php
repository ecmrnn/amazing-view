<?php

namespace App\Livewire\App\Users;

use App\Enums\UserStatus;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeactivateUser extends Component
{
    use DispatchesToast;
    
    public $user;
    #[Validate] public $password;
    #[Validate] public $password_admin;
    public $cancel_reservations;

    public function rules() {
        return [
            'password' => 'required',
            'password_admin' => 'nullable',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }
    
    public function deactivateUser() {
        if ($this->user) {
            if ($this->user->hasRole('admin')) {
                $this->validate([
                    'password' => $this->rules()['password'],
                    'password_admin' => 'required',
                ]);
            } else {
                $this->validate([
                    'password' => $this->rules()['password'],
                ]);
            }
    
            $auth = new AuthService;
    
            if ($auth->validatePassword($this->password)) {
                $service = new UserService;
                
                if ($this->user->id == Auth::user()->id) {
                    $this->toast('Deactivation Failed', 'warning', 'You cannot deactivate your own account!');
                    $this->reset('password');
                    return;
                }
    
                if ($this->user->hasRole('admin')) {
                    if ($auth->passwordMatch($this->user, $this->password_admin)) {
                        $service->deactivate($this->user, $this->cancel_reservations);
                    } else {
                        $this->addError('password_admin', 'Password mismatched, try again!');
                        return;
                    }
                } else {
                    $service->deactivate($this->user, $this->cancel_reservations);
                }
                
                $this->toast('User Deactivated', description: 'User successfully deactivated!');
                $this->dispatch('user-deactivated');
                $this->dispatch('force-logout-user');
                $this->reset('password', 'cancel_reservations', 'password_admin');
            } else {
                $this->addError('password', 'Password mismatched, try again!');
            }
        }
    }

    public function mount(User $user) {
        $this->user = $user;
    }
    
    public function render()
    {
        return <<<'HTML'
        <form wire:submit="deactivateUser" class="p-5 space-y-5" x-on:user-deactivated.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold text-red-500">Deactivate User</h2>
                <p class="text-xs">This user is about to lose access to the system</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for="password-deactivate-{{ $user->id }}">Enter your password</x-form.input-label>
                <x-form.input-text wire:model="password" type="password" label="Password" id="password-deactivate-{{ $user->id }}" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            @if ($user->role == App\Enums\UserRole::ADMIN->value)
                <x-form.input-group>
                    <x-form.input-label for="password-deactivate-admin-{{ $user->id }}">Enter the password of this Admin</x-form.input-label>
                    <x-form.input-text wire:model="password_admin" type="password" label="{{ $user->name()}}'s password" id="password-deactivate-admin-{{ $user->id }}" />
                    <x-form.input-error field="password_admin" />
                </x-form.input-group>
            @endif

            <x-form.input-checkbox id="cancel_reservations" name="cancel_reservations" label="Cancel all pending reservations of this user" wire:model.live="cancel_reservations" />

            <x-loading wire:loading wire:target='deactivateUser'>Deactivating user, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Deactivate</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
