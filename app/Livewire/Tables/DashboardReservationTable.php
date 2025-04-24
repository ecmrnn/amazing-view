<?php

namespace App\Livewire\Tables;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Services\AuthService;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
// use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

final class DashboardReservationTable extends PowerGridComponent
{
    use WithExport;

    #[Validate] public $password;

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
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage(10),
        ];
    }

    public function datasource()
    {
        return Reservation::where('status', ReservationStatus::PENDING)
            ->get();
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

            Column::action('')
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
            'view_link' => 'app.reservations.show',
        ]);
    }

    #[On('delete-reservation')]
    public function deleteReservation($id) {
        $this->validate(['password' => $this->rules()['password']]);

        $auth = new AuthService();
        
        if ($auth->validatePassword($this->password)) {
            $reservation = Reservation::find($id);

            if (!$reservation) {
                $this->toast('Missing Reservation', 'info', 'Reservation cannot be found.');
            } else {
                foreach ($reservation->rooms as $room) {
                    $room->status = RoomStatus::AVAILABLE->value;
                    $room->save();
                    
                    $reservation->rooms()->detach($room->id);
                }

                $reservation->delete();
        
                $this->toast('Success', description: 'Reservation successfully deleted');
            }
        } else {
            $this->addError('password', 'Password mismatch, try again.');
        }
        
        $this->dispatch('reservation-deleted');
        $this->reset('password');
    }
}
