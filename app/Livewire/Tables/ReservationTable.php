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
        if ($this->status) {
            return Reservation::query()
                ->with('rooms')
                ->whereHas('user', function ($query) {
                    $query->withTrashed();
                })
                ->whereStatus($this->status)
                ->orderByDesc('id');
        }

        return Reservation::query()
            ->with('rooms')
            ->whereHas('user', function ($query) {
                $query->withTrashed();
            })
            ->with('user')
            ->orderByDesc('id');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('rid')
            ->add('rid_formatted', function ($reservation) {
                return Blade::render('<div class="flex items-center gap-3"><x-copy text="' . $reservation->rid . '" /> ' . $reservation->rid . '</div>');
            })

            ->add('date_in')
            ->add('date_in_formatted', function ($reservation) {
                return Blade::render('<span class="inline-block w-max">' . date_format(date_create($reservation->date_in), 'F j, Y') . '</span>');
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                return Blade::render('<span class="inline-block w-max">' . date_format(date_create($reservation->date_out), 'F j, Y') . '</span>');
            })

            ->add('status')
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
            })
            ->add('first_name')
            ->add('first_name_formatted', function ($reservation) {
                return ucwords(strtolower($reservation->user->first_name));
            })
            ->add('last_name')
            ->add('last_name_formatted', function ($reservation) {
                return ucwords(strtolower($reservation->user->last_name));
            });
    }

    public function columns(): array
    {
        $columns = [
            Column::make('Reservation Id', 'rid_formatted', 'rid')
                ->sortable()
                ->searchable(),

            Column::make('First Name', 'first_name_formatted', 'first_name')
                ->searchable(),

            Column::make('Last Name', 'last_name_formatted', 'last_name')
                ->searchable(),

            Column::make('Check in', 'date_in_formatted', 'date_in')
                ->sortable()
                ->searchable(),

            Column::make('Check out', 'date_out_formatted', 'date_out')
                ->sortable()
                ->searchable(),
                
            Column::make('Status', 'status_formatted', 'status'),
        ];

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
