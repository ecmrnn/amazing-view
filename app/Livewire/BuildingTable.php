<?php

namespace App\Livewire;

use App\Models\Building;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
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

final class BuildingTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Building::query()->with('rooms');
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
            ->add('name_formatted', function ($building) {
                return Blade::render('<span class="inline-block min-w-max">' . $building->name . '</span>');
            })
            ->add('description')
            ->add('description_formatted', function ($building) {
                return Blade::render('<span class="line-clamp-1">' . $building->description . '</span>');
            })
            ->add('prefix')
            ->add('status_formatted', function ($building) {
                return Blade::render('<x-status type="building" :status="' . $building->status . '" />');
            })
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name_formatted', 'name'),
            Column::make('Prefix', 'prefix'),
            Column::make('Description', 'description_formatted', 'description'),
            Column::make('Status', 'status_formatted', 'status'),
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

    public function actionsFromView(Building $row): View
    {
        return view('components.table-actions.building', [
            'row' => $row,
        ]);
    }
}
