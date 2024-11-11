<?php

namespace App\Livewire\App\Users;

use App\Models\User;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateUser extends Component
{
    use DispatchesToast;

    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $phone;
    #[Validate] public $email;
    #[Validate] public $address;
    #[Validate] public $role = 0;
    #[Validate] public $password;
    #[Validate] public $password_confirmation;
    // Operations
    public $account_details = false;

    public function rules() {
        return User::rules();
    }

    public function messages() {
        return User::messages();
    }

    public function validationAttributes() {
        return User::validationAttributes();
    }

    public function accountDetails() {
        $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
        ]);

        $this->account_details = true;
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
        $user = $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'email' => $this->rules()['email'],
            'role' => $this->rules()['role'],
            'password' => $this->rules()['password'],
        ]);

        User::create($user);

        $this->toast('User Created!', 'success', 'User created successfully');    
        $this->reset();
    }

    public function render()
    {
        return view('livewire.app.users.create-user');
    }
}
