<?php

namespace App\Livewire\App\Users;

use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditUser extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'user-deactivated' => '$refresh',  
        'user-activated' => '$refresh',  
    ];

    public $user;
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $phone;
    #[Validate] public $email;
    #[Validate] public $address;
    #[Validate] public $role;
    #[Validate] public $password;

    public function rules() {
        $rules = User::rules();
        $rules['password'] = 'required';
        return $rules;
    }

    public function messages() {
        return User::messages();
    }

    public function validationAttributes() {
        return User::validationAttributes();
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

    public function submit() {
        $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
            'role' => $this->rules()['role'],
        ]);

        $this->dispatch('open-modal', 'show-user-confirmation');
    }
    
    public function update() {
        $validated = $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
            'role' => $this->rules()['role'],
        ]);

        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new UserService;
            $service->update($this->user, $validated);

            $this->toast('Success!', 'success', 'User details updated successfully');    
            $this->dispatch('user-updated');
            $this->reset('password');
            return;
        } 

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function render()
    {
        return view('livewire.app.users.edit-user');
    }
}
