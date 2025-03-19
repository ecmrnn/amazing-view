<?php

namespace App\Livewire\App\Users;

use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Illuminate\Http\Request;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPassword extends Component
{
    use DispatchesToast;
    
    public $user;
    public $email;
    #[Validate] public $password;

    public function mount(User $user) {
        $this->user = $user;
        $this->email = $user->email;
    }

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

    public function send(Request $request) {
        $this->validate([
            'password' => 'required',
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new UserService;

            $request->merge([
                'email' => $this->email,
            ]);

            $email = $service->sendPasswordResetLink($request, $this->user);

            $this->dispatch('password-resetted');
            $this->toast('Success!', description: 'Password reset link sent.');
            $this->reset('password');
            return $email;
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return <<<'HTML'
        <form class="p-5 space-y-5" wire:submit="send" x-on:password-resetted.window="show = false">
            <hgroup>
                <h2 class="text-lg font-semibold">Reset Password</h2>
                <p class="text-xs">Send a reset password link through email for guests to update their password</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='email'>Email Address</x-form.input-label>
                <x-form.input-text wire:model="email" label="Email Address" disabled />
                <x-form.input-text id="email" name="email" wire:model="email" class="sr-only" />
                <x-form.input-error field="email" />
            </x-form.input-group>

            <x-form.input-group>
                <x-form.input-label for='password-reset'>Enter your password</x-form.input-label>
                <x-form.input-text type="password" id="password-reset" name="password" wire:model="password" label="Password" />
                <x-form.input-error field="password" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='send'>Sending reset link, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button>Send Reset Password Link</x-primary-button>
            </div>
        </form>
        HTML;
    }
}
