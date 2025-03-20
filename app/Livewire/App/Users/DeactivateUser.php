<?php

namespace App\Livewire\App\Users;

use App\Enums\UserStatus;
use App\Models\User;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class DeactivateUser extends Component
{
    use DispatchesToast;
    
    public $user;
    public $password;

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
    
    public function deactivateUser() {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            $user = User::find($this->user->id);

            if ($user->id == Auth::user()->id) {
                $this->toast('Deactivation Failed', 'warning', 'You cannot deactivate your own account!');
                $this->reset('password');
                return;
            }
            
            if ($user) {
                $service = new UserService;
                $service->deactivate($user);
                $this->toast('User Deactivated', 'success', 'User successfully deactivated!');
                $this->dispatch('user-deactivated');
                $this->dispatch('force-logout-user');
                $this->reset('password');
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

            @if ($user->hasRole('guest'))
                <x-danger-message>
                    <p class="text-xs">Deactivating this user will cancel all of their in process reservations</p>
                </x-danger-message>
            @endif

            <x-form.input-group>
                <x-form.input-label for="password-deactivate-{{ $user->id }}">Enter your password</x-form.input-label>
                <x-form.input-text wire:model="password" type="password" label="Password" id="password-deactivate-{{ $user->id }}" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='deactivateUser'>Deactivating user, please wait</x-loading>
            
            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-danger-button>Deactivate</x-danger-button>
            </div>
        </form>
        HTML;
    }
}
