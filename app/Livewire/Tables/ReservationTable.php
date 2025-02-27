<?php

namespace App\Livewire\tables;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Services\AuthService;
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
use Livewire\Attributes\Validate;

final class ReservationTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'ReservationTable';
    #[Url] public $status;
    #[Validate] public string $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

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
                if (empty($reservation->resched_date_in)) {
                    return Carbon::parse($reservation->date_in)->format('F j, Y');
                } else {
                    return Carbon::parse($reservation->resched_date_in)->format('F j, Y');
                }
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                if (empty($reservation->resched_date_out)) {
                    return Carbon::parse($reservation->date_out)->format('F j, Y');
                } else {
                    return Carbon::parse($reservation->resched_date_out)->format('F j, Y');
                }
            })

            ->add('status')
            ->add('status_update', function ($reservation) {
                if (in_array($reservation->status, [ReservationStatus::AWAITING_PAYMENT->value, ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value])) {
                    $date_in = $reservation->resched_date_in == null ? $reservation->date_in : $reservation->resched_date_in;

                    if ($reservation->status == ReservationStatus::AWAITING_PAYMENT->value) {
                        $reservation_statuses = [
                            '' => 'Update Status',
                            ReservationStatus::PENDING->value => 'Pending',
                        ];
                    } elseif ($reservation->status == ReservationStatus::PENDING->value) {
                        $reservation_statuses = [
                            '' => 'Update Status',
                            ReservationStatus::CONFIRMED->value => 'Confirm',
                        ];
                    } elseif ($reservation->status == ReservationStatus::CONFIRMED->value && $date_in == Carbon::now()->format('Y-m-d')) {
                        $reservation_statuses = [
                            '' => 'Update Status',
                            ReservationStatus::CHECKED_IN->value => 'Check-in',
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
                                        <h2 class="text-lg font-semibold capitalize">Update Status</h2>
                                        <p class="max-w-sm text-xs">You are about to update this reservation by <strong class="text-blue-500 capitalize">{{ $reservation->first_name . " " . $reservation->last_name}}</strong>, proceed?</p>
                                    </hgroup>
                    
                                    <div class="flex items-end justify-end gap-1">
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
    
                            <x-modal.full :click_outside="false" name="show-checkin-confirmation-{{ $reservation->id }}" maxWidth="md">
                                <div x-on:cancel-confirmation.window="selected_value = default_value">
                                    @if ($reservation->status != 8)
                                        <section class="p-5 space-y-5 bg-white">
                                            <hgroup>
                                                <h2 class="text-lg font-semibold capitalize">Update Status</h2>
                                                <p class="max-w-sm text-xs">You are about to update this reservation by <strong class="text-blue-500 capitalize">{{ $reservation->first_name . " " . $reservation->last_name}}</strong>, proceed?</p>
                                            </hgroup>
                                            <div class="flex items-center justify-end gap-1">
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
                                        <livewire:app.invoice.create-payment :invoice="$reservation->invoice->id" />
                                    @endif
                                </div>
                            </x-modal.full>
                        </div> ', ['reservation' => $reservation, 'options' => $reservation_statuses, 'selected' => intval($reservation->status)]
                    );
                } elseif (in_array($reservation->status, [])) {
                    return Blade::render('<a wire:navigate class="text-xs text-zinc-800/50" href="{{ route(\'app.reservations.edit\', [\'reservation\' => \'' . $reservation->rid . '\']) }}">View Reservation to Edit</a>');
                } else {
                    return Blade::render('<span class="text-xs text-zinc-800/50">---</span>');
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
                
            Column::make('Status', 'status_formatted', 'status'),
        ];

        if (in_array($this->status, [ReservationStatus::AWAITING_PAYMENT->value, ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value])) {
            $columns[] = Column::make('Update status', 'status_update');
        }

        $columns[] = Column::make('Note', 'note_formatted', 'note');
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

        if (!empty($reservation)) {
            if (Auth::user()->role == User::ROLE_FRONTDESK || Auth::user()->role == User::ROLE_ADMIN) {
                $reservation->status = $status;
                
                $reservation->save();
    
                if ($reservation->status == ReservationStatus::CHECKED_IN) {
                    foreach ($reservation->rooms as $room) {
                        $room->status = RoomStatus::OCCUPIED;
                        $room->save();
                    }
                }
    
                $this->toast('Success!', description: 'Reservation status updated successfully.');
                $this->dispatch('status-changed');
                $this->dispatch('pg:eventRefresh-ReservationTable');
                $this->redirect(route('app.reservations.index', ['status' => $this->status]));
            }
        } else {
            $this->toast('Error!', description: 'Reservation not found.');
        }
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
