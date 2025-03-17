<?php

namespace App\Livewire\App\Users;

use Livewire\Attributes\Url;
use Livewire\Component;

class ShowUsers extends Component
{
    public $user_by_status = [
        'all' => 0,
        'active' => 0,
        'inactive' => 0,
    ];
    public $user_by_role = [
        'all' => 0,
        'guest' => 0,
        'receptionist' => 0,
        'admin' => 0,
    ];
    #[Url] public $status;
    #[Url] public $role;

    public function render()
    {
        return view('livewire.app.users.show-users');
    }
}
