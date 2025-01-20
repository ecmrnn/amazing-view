<?php

namespace App\Livewire\tables;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Column;
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

    public string $tableName = 'ReservationTable';

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
        return Reservation::query()
            ->whereNot('status', Reservation::STATUS_CANCELED)
            ->orderByDesc('rid');
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
            ->add('date_in_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_in)->format('F j, Y'); //20/01/2024 10:05
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_out)->format('F j, Y'); //20/01/2024 10:05
            })

            ->add('status')
            ->add('status_update', function ($reservation) {
                $reservation_statuses = [
                    1 => 'Pending',
                    0 => 'Confirmed',
                    3 => 'Checked-in',
                ];

                if ($reservation->status != Reservation::STATUS_PENDING) {
                    $reservation_statuses = [
                        0 => 'Confirmed',
                        3 => 'Checked-in',
                    ];
                }

                return Blade::render('
                <div x-data="{ selected_value: @js($selected), default_value: @js($selected) }">
                    <x-form.select type="occurrence"
                        :options=$options
                        :selected=$selected
                        x-model="selected_value"
                        x-bind:disabled="selected_value == 3"
                        x-on:change="
                            selected_value = $event.target.value;
                            $wire.selected_value = $event.target.value;
                            if ($event.target.value == 0) {
                                $dispatch(\'open-modal\', \'show-checkin-confirmation-{{ $reservation->id }}\');
                            } else {
                                $dispatch(\'open-modal\', \'show-update-status-confirmation-{{ $reservation->id }}\');
                            }
                            "
                        />
                </div> ', ['reservation' => $reservation, 'options' => $reservation_statuses, 'selected' => intval($reservation->status)]);
            })
            ->add('status_formatted', function ($reservation) {
                return Blade::render('<x-status type="reservation" :status="' . $reservation->status . '" />');
            })

            ->add('note')
            ->add('note_formatted', function ($reservation) {
                return Blade::render(
                    '<x-tooltip :textWrap="false" text="' . html_entity_decode($reservation->note, ENT_QUOTES, 'UTF-8')  . '" dir="top">
                        <div x-ref="content" class="max-w-[250px] line-clamp-1">' . html_entity_decode($reservation->note) . '</div>
                    </x-tooltip>'
                );
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Reservation Id', 'rid', 'rid')
                ->sortable()
                ->searchable(),

            Column::make('Check in', 'date_in_formatted', 'date_in')
                ->sortable()
                ->searchable(),

            Column::make('Check out', 'date_out_formatted', 'date_out')
                ->sortable()
                ->searchable(),

            Column::make('Note', 'note_formatted', 'note'),

            Column::make('Status', 'status_formatted', 'status'),

            Column::make('Update status', 'status_update'),

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
                    ['status' => 2, 'name' => 'Expired'],
                    ['status' => 3, 'name' => 'Checked-in'],
                    ['status' => 4, 'name' => 'Checked-out'],
                    ['status' => 5, 'name' => 'Completed'],
                    ['status' => 6, 'name' => 'Canceled'],
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

            $this->dispatch('status-changed');
            $this->dispatch('pg:eventRefresh-ReservationTable');
        }
    }
}
