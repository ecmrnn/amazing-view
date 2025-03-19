<?php

namespace App\Livewire\Tables;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\DispatchesToast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UserTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public $user;
    public $key;
    #[Validate] public $password;
    #[Url] public $status;
    #[Url] public $role;

    public string $tableName = 'UserTable';

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

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        $query = User::query();

        // Handle status filtering
        if (isset($this->status) && $this->status == UserStatus::INACTIVE->value) {
            $query->onlyTrashed();
        }

        // Handle role filtering
        if (isset($this->role) && $this->role != UserRole::ALL->value) {
            $query->whereRole($this->role);
        }

        return $query->orderByDesc('created_at');
    }

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.user');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('first_name', fn($user) => e(ucwords($user->first_name)))
            ->add('last_name', fn($user) => e(ucwords($user->last_name)))
            ->add('address')
            ->add('address_formatted', function ($user) {
                if (!empty($user->address)) {
                    return Blade::render('<span class="line-clamp-1 hover:line-clamp-none">' . $user->address . '</span>');
                }

                return Blade::render('<span class="text-xs text-zinc-800/50">---</span>');
            })
            ->add('phone')
            ->add('phone_formatted', function ($user) {
                return substr($user->phone, 0, 4) . ' ' . substr($user->phone, 4, 3) . ' ' . substr($user->phone, 7);
            })
            ->add('email')
            ->add('role')
            ->add('role_formatted', function($user) {
                return Blade::render('<x-role role="{{ $user->role }}" />', ['user' => $user]);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            // Column::make('Id', 'id'),
            Column::make('First Name', 'first_name', 'first_name')
                ->sortable()
                ->searchable(),
            
            Column::make('Last Name', 'last_name', 'last_name')
                ->sortable()
                ->searchable(),
            
            Column::make('Address', 'address_formatted', 'address'),

            Column::make('Phone', 'phone_formatted', 'phone')
                ->searchable(),
            
            Column::make('Email', 'email', 'email')
                ->searchable(),
            
            Column::make('Role', 'role_formatted', 'role')
                ->sortable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('role', 'role')
                ->dataSource([
                    ['role' => 0, 'name' => 'Guest'],
                    ['role' => 1, 'name' => 'Receptionist'],
                    ['role' => 2, 'name' => 'Admin'],
                ])
                ->optionLabel('name')
                ->optionValue('role'),
        ];
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.user', [
            'row' => $row,
        ]);
    }

    public function deactivateUser(Request $request, $id) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $user = User::find($id);
            $service = new UserService;
            
            if ($user->id == Auth::user()->id) {
                $this->toast('Deactivation Failed', 'warning', 'You cannot deactivate your own account!');
                $this->reset('password');
                return;
            }

            $service->deactivate($user);

            if ($user) {
                $this->fillData();
                $this->toast('User Deactivated', description: 'User successfully deactivated!');
                $this->dispatch('user-status-changed');
                $this->reset('password');
            }
        } else {
            $this->addError('password', 'Password mismatched, try again!');
        }
    }

    public function activateUser($id) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $user = User::withTrashed()->find($id);
            $service = new UserService;
            $service->activate($user);
            
            if ($user) {
                $this->fillData();
                $this->toast('User Activated', description: 'User successfully activated!');
                $this->dispatch('user-status-changed');
                $this->reset('password');
            }
        } else {
            $this->addError('password', 'Password mismatched, try again!');
        }
    }
}
