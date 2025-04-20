<?php

namespace App\Livewire\Tables;

use App\Models\RoomInclusion;
use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

final class RoomInclusionsTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public $room;
    #[Validate] public $name;
    #[Validate] public $password;

    public string $tableName = 'RoomInclusionsTable';

    public function rules() {
        return [
            'name' => 'required',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Name your inclusion',
            'password.required' => 'Enter your password',
        ];
    }

    public function setUp(): array
    {
        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        if ($this->room) {
            return RoomInclusion::query()->whereBelongsTo($this->room);
        }

        return RoomInclusion::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name', 'name'),
            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    public function actionsFromView(RoomInclusion $row)
    {
        return view('components.table-actions.room_inclusion', [
            'row' => $row,
        ]);
    }

    public function updateInclusion(RoomInclusion $inclusion, $name) {
        $this->name = $name;

        $this->validate(['name' => $this->rules()['name']]);

        $inclusion->update([
            'name' => $this->name,
        ]);

        $this->reset('name');
        $this->dispatch('inclusion-updated');
        $this->toast('Success!', description: 'Room inclusion updated!');
    }    

    public function deleteInclusion(RoomInclusion $inclusion) {
        $this->validate(['password' => $this->rules()['password']]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $inclusion->delete();

            $this->reset('password');
            $this->dispatch('inclusion-deleted');
            $this->toast('Success!', description: 'Room inclusion deleted!');
            return;
        }

        $this->addError('password', 'Password does not match, try again');
    }
}
