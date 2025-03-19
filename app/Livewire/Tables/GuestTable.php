<?php

namespace App\Livewire\Tables;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\Url;
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

final class GuestTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'GuestTable';
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
        $query = Reservation::query()->with('rooms')
            ->whereDate('date_in', '<=', Carbon::today())
            ->whereDate('date_out', '>=', Carbon::today())
            ->orderByDesc('rid');

        if (!empty($this->status)) {
            $query = Reservation::query()
                ->where(function ($query) {
                    $query->where('date_in', '<=', Carbon::today())
                        ->where('date_out', '>=', Carbon::today());
                })
                ->where('status', $this->status)
                ->orderByDesc('rid');
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
                $style = 'bg-green-500';
                if ($reservation->date_out == Carbon::now()->format('Y-m-d')) {
                    $style = 'bg-amber-500';
                } elseif ($reservation->date_out < Carbon::now()->format('Y-m-d')) {
                    $style = 'bg-red-500';
                }
                return Blade::render('<div class="flex items-center gap-3 font-semibold"><div class="w-2 rounded-full aspect-square ' . $style . '"></div>' . $reservation->rid . '</div>');
            })

            ->add('first_name')
            ->add('first_name_formatted', function($reservation) {
                return ucwords(strtolower($reservation->user->first_name));
            })
            
            ->add('last_name')
            ->add('last_name_formatted', function($reservation) {
                return ucwords(strtolower($reservation->user->last_name));
            })


            ->add('date_in')
            ->add('date_in_formatted', function ($reservation) {
                $date_in = $reservation->date_in;
                $date_out = $reservation->date_out;

                if ($date_in == $date_out) {
                    return Carbon::parse($date_in)->format('F j, Y') . ' - ' . '8:00 AM';
                } 

                return Carbon::parse($date_in)->format('F j, Y') . ' - ' . '2:00 PM';
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                $date_in = $reservation->date_in;
                $date_out = $reservation->date_out;

                if ($date_in == $date_out) {
                    return Carbon::parse($date_out)->format('F j, Y') . ' - ' . '6:00 PM';
                } 

                return Carbon::parse($date_out)->format('F j, Y') . ' - ' . '12:00 PM';
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

            Column::make('First Name', 'first_name_formatted', 'first_name')
                ->sortable()
                ->searchable(),

            Column::make('Last Name', 'last_name_formatted', 'last_name')
                ->sortable()
                ->searchable(),

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

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
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
