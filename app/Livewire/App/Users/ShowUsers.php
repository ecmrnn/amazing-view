<?php

namespace App\Livewire\App\Users;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShowUsers extends Component
{
    protected $listeners = [
        'user-status-changed' => '$refresh'
    ];

    public $user_by_status = [
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
    public $user_count;

    public function getUsers() {
        $statuses = [
            'active' => UserStatus::ACTIVE->value,
            'inactive' => UserStatus::INACTIVE->value,
        ];
        $roles = [
            'guest' => UserRole::GUEST->value,
            'receptionist' => UserRole::RECEPTIONIST->value,
            'admin' => UserRole::ADMIN->value,
        ];

        $status_counts = User::selectRaw('status, COUNT(*) as count')
            ->withTrashed()
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('count', 'status');

        $role_counts = User::selectRaw('role, COUNT(*) as count')
            ->withTrashed()
            ->whereIn('role', $roles)
            ->where('status', $this->status)
            ->groupBy('role')
            ->pluck('count', 'role');

        foreach ($statuses as $key => $status) {
            $this->user_by_status[$key] = $status_counts->get($status, 0);
        }
        foreach ($roles as $key => $role) {
            $this->user_by_role[$key] = $role_counts->get($role, 0);
        }

        $query = User::query();

        // Handle status filtering
        if (isset($this->status) && $this->status == UserStatus::INACTIVE->value) {
            $query->onlyTrashed();
        }

        // Handle role filtering
        if (isset($this->role) && $this->role != UserRole::ALL->value) {
            $query->whereRole($this->role);
        }

        $this->user_count = $query->count();
        $this->user_by_role['all'] = $role_counts->sum();

    }

    public function render()
    {
        $this->getUsers();
        if (empty(request()->query())) {
            request()->merge([
                'role' => $this->role,
                'status' => $this->status,
            ]);
        }

        return view('livewire.app.users.show-users');
    }
}
