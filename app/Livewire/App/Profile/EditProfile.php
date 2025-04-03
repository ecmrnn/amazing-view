<?php

namespace App\Livewire\App\Profile;

use App\Models\User;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

class EditProfile extends Component
{
    use DispatchesToast;

    public $user;
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $phone;
    #[Validate] public $address;
    #[Validate] public $password;
    #[Validate] public $password_confirmation;
    public $email;

    public $checks = [
        'min' => false,
        'uppercase' => false,
        'lowercase' => false,
        'numbers' => false,
        'symbols' => false,
    ];

    public function rules() {
        return User::rules(['role']);
    }

    public function messages() {
        return User::messages(['role']);
    }

    public function mount(User $user) {
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->email = $user->email;
    }

    public function validatePassword() {
        $service = new UserService;
        $this->checks = $service->validatePassword($this->password);

        // Prevent triggering validation of other fields
        $this->resetErrorBag('password', 'password_confirmation');
    }

    public function saveProfile() {
        $validated = $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
        ]);

        $service = new UserService;
        $service->update($this->user, $validated);

        $this->toast('Success!', description: 'Profile edited successfully!');
    }

    public function saveAccount() {
        $validated = $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $service = new UserService;
        $service->update($this->user, $validated);

        $this->toast('Success!', description: 'Account edited successfully!');
    }

    public function render()
    {
        return view('livewire.app.profile.edit-profile');
    }
}
