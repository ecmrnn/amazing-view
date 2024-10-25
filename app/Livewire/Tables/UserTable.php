<?php

namespace App\Livewire\Tables;

use App\Models\User;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
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
    #[Validate()] public $password;

    public string $tableName = 'UserTable';

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            // Exportable::make('export')
            //     ->striped()
            //     ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()->whereStatus(User::STATUS_ACTIVE);
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
            ->add('phone')
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
            Column::make('First Name', 'first_name')
                ->searchable(),
            
            Column::make('Last Name', 'last_name')
                ->searchable(),
            
            Column::make('Address', 'address', 'address'),

            Column::make('Phone', 'phone', 'phone')
                ->searchable(),
            
            Column::make('Email', 'email', 'email')
                ->searchable(),
            
            Column::make('Role', 'role_formatted', 'role'),

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
            'edit_link' => 'app.users.edit',
            'view_link' => 'app.users.show',
        ]);
    }

    // #[On('')]
    public function deactivateUser($id) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {
            // deactivate user
            $user = User::query()->find($id)->update([
                'status' => User::STATUS_INACTIVE
            ]);

            if ($user) {
                $this->fillData();
                $this->toast('User Deactivated', 'success', 'User successfully deactivated!');
                $this->dispatch('pg:eventRefresh-UserTable');
                $this->dispatch('user-deactivated');
                // reset
                $this->reset('password');
            }
        }
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
