<?php

namespace App\Livewire\Tables;

use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class RoomTable extends PowerGridComponent
{
    use WithExport;

    public $room_type_id;

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.rooms', ['room_type_id' => $this->room_type_id]);
    }

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function setUp(): array
    {
        return [
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        return Room::where('room_type_id', $this->room_type_id);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $room_statuses = ['Available', 'Unavailable', 'Occupied', 'Reserved'];

        return PowerGrid::fields()
            ->add('id')
            ->add('image_1_path', function ($room) {
                return '<img class="rounded-lg" style="max-width: 50px;" src="' . $room->image_1_path . '" />';
            })

            ->add('room_number')
            ->add('room_type_id')
            ->add('room_type_id_formatted', function ($room) {
                return $room->roomType->name;
            })

            ->add('max_capacity')
            ->add('rate')

            ->add('status_update', function ($room) {
                return Blade::render('<x-form.select />');
            })
            ->add('status_update', function ($room) use ($room_statuses) {
                return Blade::render('<x-form.select type="occurrence" :options=$options  :selected=$selected wire:change="statusChanged($event.target.value, {{ $room->id }})"  />', ['room' => $room, 'options' => $room_statuses, 'selected' => intval($room->status)]);
            })
            ->add('status_formatted', function ($room) {
                return Blade::render('<x-status type="room" :status="' . $room->status . '" />');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Thumbnail', 'image_1_path'),

            Column::make('Room Type', 'room_type_id_formatted', 'room_type_id')
                ->sortable()
                ->searchable(),

            Column::make('Room Number', 'room_number')
                ->sortable()
                ->searchable(),

            Column::make('Max. Capacity', 'max_capacity'),

            Column::make('Rate', 'rate')
                ->sortable(),

            Column::make('Status', 'status_formatted', 'status'),

            Column::make('Update Status', 'status_update'),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('status', 'status')
                ->dataSource([
                    ['status' => 0, 'name' => 'Available'],
                    ['status' => 1, 'name' => 'Unavailable'],
                    ['status' => 2, 'name' => 'Occupied'],
                    ['status' => 3, 'name' => 'Reserved'],
                ])
                ->optionLabel('name')
                ->optionValue('status'),

            Filter::number('rate', 'rate')
                ->thousands('.')
                ->placeholder('Min. Rate', 'Max. Rate')
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.room', [
            'row' => $row,
            'edit_link' => 'app.room.edit',
        ]);
    }

    #[On('statusChanged')]
    public function statusChanged($status, $id) {
        $room = Room::findOrFail($id);

        if (Auth::user()->role == User::ROLE_FRONTDESK || Auth::user()->role == User::ROLE_ADMIN) {
            $room->status = $status;
            $room->save();
        }
    }
}
