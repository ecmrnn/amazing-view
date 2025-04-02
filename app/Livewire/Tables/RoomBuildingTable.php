<?php

namespace App\Livewire\Tables;

use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class RoomBuildingTable extends PowerGridComponent
{
    use WithExport;

    public $building;
    public string $tableName = 'RoomBuildingTable';

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.buildings');
    }

    public function datasource(): Builder
    {
        return Room::query()->whereBelongsTo($this->building);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('room_number')
            ->add('max_capacity')
            ->add('rate_formatted', function ($room) {
                return Blade::render('<x-currency />' . number_format($room->rate, 2));
            })
            ->add('status_formatted', function ($room) {
                return Blade::render('<x-status type="room" :status="' . $room->status . '" />');
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Room Number', 'room_number'),
            Column::make('Max Capacity', 'max_capacity'),
            Column::make('Rate', 'rate_formatted', 'rate'),
            Column::make('Status', 'status_formatted', 'status'),
            Column::action(''),
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

    public function actionsFromView(Room $row): View
    {
        return view('components.table-actions.room', [
            'row' => $row,
        ]);
    }
}
