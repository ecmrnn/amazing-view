<?php

namespace App\Livewire\tables;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
use Illuminate\View\View;
use Livewire\Attributes\On;

final class ReservationTable extends PowerGridComponent
{
    use WithExport;

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.reservations');
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
        return Reservation::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $reservation_statuses = ['Confirmed', 'Pending', 'Expired', 'Checked in', 'Checked out', 'Completed'];

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
            ->add('status_update', function ($reservation) use ($reservation_statuses) {
                return Blade::render('<x-form.select type="occurrence" :options=$options  :selected=$selected wire:change="statusChanged($event.target.value, {{ $room->id }})"  />', ['room' => $reservation, 'options' => $reservation_statuses, 'selected' => intval($reservation->status)]);
            })
            ->add('status_formatted', function ($reservation) {
                return Blade::render('<x-status type="reservation" :status="' . $reservation->status . '" />');
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

            Column::make('Update status', 'status_update'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('rid')
                ->placeholder('Reservation ID'),

            Filter::select('status', 'status')
                ->dataSource([
                    ['status' => 0, 'name' => 'Confirmed'],
                    ['status' => 1, 'name' => 'Pending'],
                    ['status' => 2, 'name' => 'Expired'],
                    ['status' => 3, 'name' => 'Checked in'],
                    ['status' => 4, 'name' => 'Checked out'],
                    ['status' => 5, 'name' => 'Completed'],
                ])
                ->optionLabel('name')
                ->optionValue('status'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.reservation', [
            'row' => $row,
            'edit_link' => 'app.reservations.edit',
        ]);
    }

    #[On('statusChanged')]
    public function statusChanged($status, $id) {
        $reservation = Reservation::findOrFail($id);

        if (Auth::user()->role == User::ROLE_FRONTDESK || Auth::user()->role == User::ROLE_ADMIN) {
            $reservation->status = $status;
            
            $reservation->save();

            if ($reservation->status == Reservation::STATUS_CHECKED_IN) {
                foreach ($reservation->rooms as $room) {
                    $room->status = Room::STATUS_OCCUPIED;
                    $room->save();
                }
            } else {
                foreach ($reservation->rooms as $room) {
                    $room->status = Room::STATUS_AVAILABLE;
                    $room->save();
                }
            }
        }
    }
}
