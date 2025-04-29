<?php

namespace App\Livewire\Tables;

use App\Enums\ReservationStatus;
use App\Http\Controllers\DateController;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\Url;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class GuestTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'GuestTable';
    public string $primaryKey = 'reservations.id';
    public string $sortField = 'reservations.date_in';
    public string $sortDirection = 'desc';
    #[Url] public $status;

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.guest');
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
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        $query = Reservation::query()
            ->with('user')
            ->select([
                'reservations.*',
                'users.first_name',
                'users.last_name',
            ])
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->where(function ($query) {
                $query->whereDate('date_in', DateController::today())
                      ->orWhere('reservations.status', ReservationStatus::CHECKED_IN->value);
            });
    
        if ($this->status) {
            $query = Reservation::query()
                ->with('user')
                ->select([
                    'reservations.*',
                    'users.first_name',
                    'users.last_name',
                ])
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->whereDate('date_in', DateController::today())
                ->where('reservations.status', $this->status)
                ->orWhere(function ($q) {
                    if ($this->status == ReservationStatus::CHECKED_IN->value) {
                        $q->where('reservations.status', ReservationStatus::CHECKED_IN->value);
                    }
                });
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('rid')
            ->add('rid_formatted', function ($reservation) {
                $ping = 'bg-green-400';
                $style = 'bg-green-50 border border-green-500';
                if ($reservation->date_out == Carbon::now()->format('Y-m-d')) {
                    $ping = 'bg-amber-400';
                    $style = 'bg-amber-50 border border-amber-500';
                } elseif ($reservation->date_out < Carbon::now()->format('Y-m-d')) {
                    $ping = 'bg-red-400';
                    $style = 'bg-red-50 border border-red-500';
                }
                return Blade::render('
                    <div class="flex items-center gap-3 font-semibold">
                        <span class="relative flex size-2">
                            <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping ' . $ping . '"></span>
                            <span class="relative inline-flex rounded-full size-2 ' . $style . '"></span>
                        </span>' . 
                        $reservation->rid .
                    '</div>');
            })

            ->add('name')
            ->add('name_formatted', function ($reservation) {
                return Blade::render('<span class="inline-block w-max">' . ucwords(strtolower($reservation->user->first_name)) . " " . ucwords(strtolower($reservation->user->last_name)) . '</span>');
            })

            ->add('date_in')
            ->add('date_in_formatted', function ($reservation) {
                return Blade::render('<span class="inline-block w-max">' . date_format(date_create($reservation->date_in), 'F j, Y') . ' - ' . date_format(date_create($reservation->time_in), 'g:i A') . '</span>');
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                return Blade::render('<span class="inline-block w-max">' . date_format(date_create($reservation->date_out), 'F j, Y') . ' - ' . date_format(date_create($reservation->time_out), 'g:i A') . '</span>');
            })

            ->add('status_formatted', function ($reservation) {
                return Blade::render('<x-status type="reservation" :status="' . $reservation->status . '" />');
            })

            ->add('created_at')
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('Reservation Id', 'rid_formatted', 'rid')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name_formatted', 'first_name')
                ->searchableRaw('CONCAT(`first_name`, " ", `last_name`)'),

            Column::make('Check-in Date', 'date_in_formatted', 'date_in'),

            Column::make('Check-out Date', 'date_out_formatted', 'date_out'),

            Column::make('status', 'status_formatted', 'status'),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('rid')
                ->placeholder('Reservation ID'),

            Filter::inputText('first_name')
                ->placeholder('First Name'),
        ];
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.guest', [
            'row' => $row,
            'edit_link' => 'app.reservations.edit',
            'view_link' => 'app.reservations.show',
        ]);
    }

    public function checkIn(Reservation $reservation) {
        $service = new ReservationService;
        $service->checkIn($reservation);

        $this->toast('Success!', description: 'Guest successfully checked in!');
        $this->dispatch('guest-checked-in');
        $this->dispatch('pg:eventRefresh-GuestTable');
    }
}
