<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
// use PowerComponents\LivewirePowerGrid\Exportable;
// use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class DashboardReservationTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            // Exportable::make('export')
            //     ->striped()
            //     ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            // Header::make()
            //     ->showToggleColumns()
            //     ->showSearchInput(),

            Footer::make()
                ->showPerPage(10)
        ];
    }

    public function datasource(): Builder
    {
        return Reservation::query()->where('status', Reservation::STATUS_PENDING)
            ->orWhere('status', Reservation::STATUS_CONFIRMED);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('rid')
            ->add('date_in')
            ->add('date_out')
            ->add('date_in_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_in)->format('F j, Y'); //20/01/2024 10:05
            })
            ->add('date_out_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_out)->format('F j, Y'); //20/01/2024 10:05
            })
            ->add('status')
            ->add('status_formatted', function ($reservation) {
                return Blade::render('<x-status.reservation :status="' . $reservation->status . '" />');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Reservation Id', 'rid', 'rid')
                ->sortable()
                ->searchable(),
            
            Column::make('Check in', 'date_in_formatted', 'date_in'),

            Column::make('Check out', 'date_out_formatted', 'date_out'),

            Column::make('Status', 'status_formatted', 'status'),

            Column::action('Action')
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

    // public function actions(Reservation $row): array
    // {
    //     return [
    //     ];
    // }

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
