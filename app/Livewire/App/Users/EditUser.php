<?php

namespace App\Livewire\App\Users;

use App\Models\User;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditUser extends Component
{
    use DispatchesToast;

    public $user;
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $phone;
    #[Validate] public $email;
    #[Validate] public $address;
    #[Validate] public $role = 0;

    public function rules() {
        return User::rules(['password']);
    }

    public function messages() {
        return User::messages(['password']);
    }

    public function validationAttributes() {
        return User::validationAttributes(['password']);
    }

    public function mount(User $user) {
        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->address = $user->address;
        $this->role = $user->role;
    }

    public function createUser() {
        $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'role' => $this->rules()['role'],
        ]);

        $this->dispatch('open-modal', 'show-user-confirmation');
    }
    
    public function update() {
        $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'role' => $this->rules()['role'],
        ]);

        $this->user->first_name = $this->first_name;
        $this->user->last_name = $this->last_name;
        $this->user->phone = $this->phone;
        $this->user->role = $this->role;
        $this->user->save();

        $this->toast('Success!', 'success', 'User details updated successfully');    
    }

    public function render()
    {
        return view('livewire.app.users.edit-user');
    }
}
