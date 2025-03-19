<?php

namespace App\Livewire\App\Users;

use App\Enums\SessionStatus;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ShowUser extends Component
{
    public $user;
    public $session_status;
    public $reservation_count;

    public function mount(User $user) {
        $this->user = $user;
        $this->reservation_count = $user->reservations->count();
        $this->getSessionStatus();
    }

    #[On('force-logout-user')]
    public function getSessionStatus() {
        $this->session_status = SessionStatus::OFFLINE->value;
        
        $session = DB::table('sessions')->where('user_id', $this->user->id)->first();
        
        if (!empty($session)) {
            $this->session_status = SessionStatus::ONLINE->value;
        }
    }

    public function render()
    {
        return view('livewire.app.users.show-user');
    }
}
