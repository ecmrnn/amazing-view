<?php

namespace App\Livewire\Tables;

use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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

final class RoomTable extends PowerGridComponent
{
    use WithExport;
    
    public bool $showFilters = true;

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Header::make()
                ->showToggleColumns(),

            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        return Room::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('image_1_path', function($room) {
                return '<img class="rounded-lg" style="max-width: 50px;" src="' . $room->image_1_path . '" />';
            })

            ->add('room_number')
            ->add('room_type_id')
            ->add('room_type_id_formatted', function ($room) {
                return $room->roomType->name;
            })

            ->add('max_capacity')
            ->add('rate')

            ->add('status')
            ->add('status_formatted', function($room) {
                return Blade::render('<x-status type="room" :status="' . $room->status . '" />');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),
            
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
        $this->js('alert('.$rowId.')');
    }

    public function actionsFromView($row) {
        $actions = ['edit', 'delete', 'view'];

        return view('components.table-actions', [
            'row' => $row,
            'actions' => $actions,
        ]);
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
