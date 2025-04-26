<?php

namespace App\Livewire\Tables;

use App\Enums\SessionStatus;
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
use Illuminate\Support\Facades\DB;
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
    public string $sortDirection = 'desc';
    
    #[Validate] public $password;
    #[Validate] public $password_admin;
    public $cancel_reservations;
    #[Url] public $status;
    #[Url] public $role;

    public string $tableName = 'UserTable';

    public function rules() {
        return [
            'password' => 'required',
            'password_admin' => 'nullable',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
            'password_admin.required' => 'Enter password of this Admin',
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
                ->withoutLoading()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        $query = User::query();
        $query->whereStatus($this->status);

        // Handle role filtering
        if (isset($this->role) && $this->role != UserRole::ALL->value) {
            $query->whereRole($this->role);
        }
        
        return $query;
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
                return Blade::render('<span class="inline-block w-max">' . substr($user->phone, 0, 4) . ' ' . substr($user->phone, 4, 3) . ' ' . substr($user->phone, 7) . '</span>');
            })
            ->add('email')
            ->add('role')
            ->add('role_formatted', function($user) {
                return Blade::render('<x-role role="{{ $user->role }}" />', ['user' => $user]);
            })
            ->add('session_status', function ($user) {
                $session = DB::table('sessions')->where('user_id', $user->id)->first();
                $status = $session ? SessionStatus::ONLINE->value : SessionStatus::OFFLINE->value;
                
                return Blade::render('<x-status type="session" :status="' . $status . '" />');
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Status', 'session_status'),

            Column::make('First Name', 'first_name')
                ->sortable()
                ->searchable(),
            
            Column::make('Last Name', 'last_name')
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
                    ['role' => UserRole::GUEST->value, 'name' => 'Guest'],
                    ['role' => UserRole::RECEPTIONIST->value, 'name' => 'Receptionist'],
                    ['role' => UserRole::ADMIN->value, 'name' => 'Admin'],
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

    public function deactivateUser($id) {
        $user = User::find($id);

        if ($user) {
            if ($user->hasRole('admin')) {
                $this->validate([
                    'password' => $this->rules()['password'],
                    'password_admin' => 'required',
                ]);
            } else {
                $this->validate([
                    'password' => $this->rules()['password'],
                ]);
            }
    
            $auth = new AuthService;
    
            if ($auth->validatePassword($this->password)) {
                $service = new UserService;
                
                if ($user->id == Auth::user()->id) {
                    $this->toast('Deactivation Failed', 'warning', 'You cannot deactivate your own account!');
                    $this->reset('password');
                    return;
                }
    
                if ($user->hasRole('admin')) {
                    if ($auth->passwordMatch($user, $this->password_admin)) {
                        $service->deactivate($user, $this->cancel_reservations);
                    } else {
                        $this->addError('password_admin', 'Password mismatched, try again!');
                        return;
                    }
                } else {
                    $service->deactivate($user, $this->cancel_reservations);
                }
                
                $this->fillData();
                $this->toast('User Deactivated', description: 'User successfully deactivated!');
                $this->dispatch('user-status-changed');
                $this->dispatch('force-logout-user');
                $this->reset('password', 'cancel_reservations', 'password_admin');
            } else {
                $this->addError('password', 'Password mismatched, try again!');
            }
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
