<?php

namespace App\Livewire\App\Users;

use App\Models\User;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

class CreateUser extends Component
{
    use DispatchesToast;

    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $phone;
    #[Validate] public $email;
    #[Validate] public $address;
    #[Validate] public $role = 1;
    #[Validate] public $password;
    #[Validate] public $password_confirmation;

    // Operations
    public $checks = [
        'min' => false,
        'uppercase' => false,
        'lowercase' => false,
        'numbers' => false,
        'symbols' => false,
    ];

    public function rules() {
        return User::rules();
    }

    public function messages() {
        return User::messages();
    }

    public function validationAttributes() {
        return User::validationAttributes();
    }

    public function validatePassword() {
        $service = new UserService;
        $this->checks = $service->validatePassword($this->password);

        // Prevent triggering validation of other fields
        $this->resetErrorBag('password', 'password_confirmation');
    }

    public function createUser() {
        $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'email' => $this->rules()['email'],
            'role' => $this->rules()['role'],
            'password' => $this->rules()['password'],
        ]);

        $this->dispatch('open-modal', 'show-user-confirmation');
    }
    
    public function store() {
        $validated = $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'email' => $this->rules()['email'],
            'role' => $this->rules()['role'],
            'password' => $this->rules()['password'],
        ]);

        $service = new UserService;
        $service->create($validated);

        $this->toast('User Created!', description: 'User created successfully');    
        $this->dispatch('user-created');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.app.users.create-user');
    }
}
