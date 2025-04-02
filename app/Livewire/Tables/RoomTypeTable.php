<?php

namespace App\Livewire\Tables;

use App\Models\RoomType;
use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class RoomTypeTable extends PowerGridComponent
{
    use DispatchesToast;

    #[Validate] public $password;

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

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput()
                ->withoutLoading(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return RoomType::query()->with('rooms');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('name_formatted', function ($room_type) {
                return Blade::render('<span class="inline-block w-max">' . $room_type->name . '</span>');
            })
            ->add('rooms_count', function ($room_type) {
                return number_format($room_type->rooms->count());
            })

            ->add('max_rate')
            ->add('max_rate_formatted', function ($room_type) {
                return Blade::render('<x-currency />' . number_format($room_type->max_rate, 2));
            })
            ->add('description')
            ->add('description_formatted', function ($room_type) {
                return Blade::render('<p class="line-clamp-1">' . $room_type->description . '</p>');
            })
            ->add('image_1_path');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name_formatted', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Rooms', 'rooms_count'),
            
            Column::make('Max rate', 'max_rate_formatted', 'max_rate')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description_formatted', 'description'),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actionsFromView($row): View
    {
        return view('components.table-actions.room_type', [
            'row' => $row
        ]);
    }

    public function deleteRoomType($room_type) {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $auth = new AuthService;
        
        if ($auth->validatePassword($this->password)) {
            $room_type = RoomType::find($room_type);

            if ($room_type->rooms->count() > 0) {
                $this->toast('Deletion Failed', 'warning', 'Room type has rooms, cannot delete');
                $this->dispatch('room-type-deleted');
                $this->reset('password');
                return;
            }

            if ($room_type->image_1_path) {
                Storage::disk('public')->delete($room_type->image_1_path);
            }
            
            if ($room_type->image_2_path) {
                Storage::disk('public')->delete($room_type->image_2_path);
            }

            if ($room_type->image_3_path) {
                Storage::disk('public')->delete($room_type->image_3_path);
            }

            if ($room_type->image_4_path) {
                Storage::disk('public')->delete($room_type->image_4_path);
            }
            
            $this->toast('Success', description: 'Room type deleted successfully!');
            $this->dispatch('room-type-deleted');
            $this->reset('password');
            return $room_type->delete();
        }

        $this->addError('password', 'Password mismatched, try again!');
        $this->reset('password');
    }
}
