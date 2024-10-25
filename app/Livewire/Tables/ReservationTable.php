<?php

namespace App\Livewire\tables;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
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
        return Reservation::query()->whereNot('status', Reservation::STATUS_CANCELED);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $reservation_statuses = ['Confirmed', 'Pending', 'Expired', 'Checked-in', 'Checked-out', 'Completed', 'Canceled'];

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
            ->add('status_update', function ($reservation) use ($reservation_statuses) {
                return Blade::render('
                <div x-data="{ selected_value: @js($selected), default_value: @js($selected) }">
                    <x-form.select type="occurrence"
                        :options=$options
                        :selected=$selected
                        x-model="selected_value"
                        x-on:change="
                            $dispatch(\'open-modal\', \'show-update-status-confirmation-{{ $reservation->id }}\');
                            selected_value = $event.target.value;
                            $wire.selected_value = $event.target.value"
                        />
                    
                    <x-modal.full :click_outside="false" name="show-update-status-confirmation-{{ $reservation->id }}" maxWidth="xs">
                        <div>
                            <section class="p-5 space-y-5 bg-white">
                                <hgroup>
                                    <h2 class="font-semibold text-center capitalize">Update Status</h2>
                                    <p class="max-w-sm text-xs text-center">You are about to update this reservation by <strong class="text-blue-500 capitalize">{{ $reservation->first_name . " " . $reservation->last_name}}</strong>, proceed?</p>
                                </hgroup>
                
                                <div class="flex items-center justify-center gap-1">
                                    <x-secondary-button type="button" x-on:click="show = false; selected_value = default_value">No, cancel</x-secondary-button>
                                    <x-primary-button type="button"
                                        wire:click="statusChanged(selected_value, {{ $reservation->id }});
                                        show = false;
                                        default_value = selected_value"
                                        >
                                        Yes, update
                                    </x-primary-button>
                                </div>
                            </section>
                        </div>
                    </x-modal.full>
                </div> ', ['reservation' => $reservation, 'options' => $reservation_statuses, 'selected' => intval($reservation->status)]);
            })
            ->add('status_formatted', function ($reservation) {
                return Blade::render('<x-status type="reservation" :status="' . $reservation->status . '" />');
            })

            ->add('note')
            ->add('note_formatted', function ($reservation) {
                return Blade::render(
                    '<x-tooltip :textWrap="false" text="' . html_entity_decode($reservation->note)  . '" dir="top">
                        <div x-ref="content" class="max-w-[250px] line-clamp-1">' . $reservation->note . '</div>
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
        }
    }
}
