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
use Livewire\Attributes\Url;

final class ReservationTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'ReservationTable';
    #[Url] public $status;

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
                ->showSearchInput()
                ->withoutLoading(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        if (isset($this->status)) {
            return Reservation::query()
                ->whereStatus($this->status)
                ->orderByDesc('rid');
        } else {
            return Reservation::query()->with('rooms')
                ->orderByDesc('rid');
        }
        
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
                if (in_array($reservation->status, [Reservation::STATUS_AWAITING_PAYMENT, Reservation::STATUS_PENDING, Reservation::STATUS_CONFIRMED])) {
                    if ($reservation->status == Reservation::STATUS_AWAITING_PAYMENT) {
                        $reservation_statuses = [
                            '' => 'Update Status',
                            Reservation::STATUS_PENDING => 'Pending',
                        ];
                    } elseif ($reservation->status == Reservation::STATUS_PENDING) {
                        $reservation_statuses = [
                            '' => 'Update Status',
                            Reservation::STATUS_CONFIRMED => 'Confirm',
                        ];
                    } elseif ($reservation->status == Reservation::STATUS_CONFIRMED) {
                        $reservation_statuses = [
                            '' => 'Update Status',
                            Reservation::STATUS_CHECKED_IN => 'Check-in',
                        ];
                    } else {
                        $reservation_statuses = [
                            '' => 'Update Status',
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
                                    if ($event.target.value == 1) {
                                        $dispatch(\'open-modal\', \'show-checkin-confirmation-{{ $reservation->id }}\');
                                    } else {
                                        $dispatch(\'open-modal\', \'show-update-status-confirmation-{{ $reservation->id }}\');
                                    }
                                    "
                            />
                            
                            <x-modal.full :click_outside="false" name="show-update-status-confirmation-{{ $reservation->id }}" maxWidth="xs">
                                <section class="p-5 space-y-5 bg-white">
                                    <hgroup>
                                        <h2 class="font-semibold capitalize">Update Status</h2>
                                        <p class="max-w-sm text-xs">You are about to update this reservation by <strong class="text-blue-500 capitalize">{{ $reservation->first_name . " " . $reservation->last_name}}</strong>, proceed?</p>
                                    </hgroup>
                    
                                    <div class="flex items-end justify-center gap-1">
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
                            </x-modal.full>
    
                            <x-modal.full :click_outside="false" name="show-checkin-confirmation-{{ $reservation->id }}" maxWidth="sm">
                                <div x-on:cancel-confirmation.window="selected_value = default_value">
                                    @if (!empty($reservation->invoice->downpayment) && intval($reservation->invoice->downpayment) != 0)
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
                                    @else
                                        
                                    @endif
                                </div>
                            </x-modal.full>
                        </div> ', ['reservation' => $reservation, 'options' => $reservation_statuses, 'selected' => intval($reservation->status)]
                    );
                } elseif (in_array($reservation->status, [])) {
                    return Blade::render('<a wire:navigate class="text-xs text-zinc-800/50" href="{{ route(\'app.reservations.edit\', [\'reservation\' => \'' . $reservation->rid . '\']) }}">View Reservation to Edit</a>');
                } else {
                    return Blade::render('<span class="text-xs text-zinc-800/50">Not Available</span>');
                }
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
        $columns = [
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

        ];

        if (in_array($this->status, [Reservation::STATUS_AWAITING_PAYMENT, Reservation::STATUS_PENDING, Reservation::STATUS_CONFIRMED])) {
            $columns[] = Column::make('Update status', 'status_update');
        }

        $columns[] = Column::action('');
        
        return $columns;
    }

    public function filters(): array
    {
        return [
            Filter::inputText('rid')
                ->placeholder('Reservation ID'),
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

            $this->toast('Success!', description: 'Reservation status updated successfully.');
            $this->dispatch('status-changed');
            $this->dispatch('pg:eventRefresh-ReservationTable');
            $this->redirect(route('app.reservations.index', ['status' => $this->status]));
        }
    }
}
