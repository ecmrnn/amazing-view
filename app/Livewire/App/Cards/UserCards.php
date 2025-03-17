<?php

namespace App\Livewire\App\Cards;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UserCards extends Component
{
    protected $listeners = ['user-deactivated' => '$refresh'];

    public function render()
    {
        $total_accounts = User::count();
        $active_accounts = User::select(DB::raw('count(*) as count'))
            ->whereStatus(UserStatus::ACTIVE)
            ->first();
        $deactivated_accounts = $total_accounts - $active_accounts->count;
        $guest_accounts = User::select(DB::raw('count(*) as count'))
            ->whereRole(UserRole::GUEST)
            ->first();

        return view('livewire.app.cards.user-cards', [
            'total_accounts' => $total_accounts,
            'active_accounts' => $active_accounts,
            'deactivated_accounts' => $deactivated_accounts,
            'guest_accounts' => $guest_accounts,
        ]);
    }
}
