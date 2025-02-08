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
        $this->showCheckBox();

        return [
            Header::make()
                ->showSearchInput()
                ->showToggleColumns(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        return Reservation::query() 
                        ->whereStatus(ReservationStatus::CHECKED_IN);
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
            ->add('last_name')
            ->add('name_formatted', function($reservation) {
                return ucwords(strtolower($reservation->first_name)) . ' ' . ucwords(strtolower($reservation->last_name));
            })


            ->add('date_in')
            ->add('date_in_formatted', function ($reservation) {
                $date_in = empty($reservation->resched_date_in) ? $reservation->date_in : $reservation->resched_date_in;
                $date_out = empty($reservation->resched_date_out) ? $reservation->date_out : $reservation->resched_date_out;

                if ($date_in == $date_out) {
                    return Carbon::parse($date_in)->format('F j, Y') . ' - ' . '8:00 AM';
                } 

                return Carbon::parse($date_in)->format('F j, Y') . ' - ' . '2:00 PM';
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                $date_in = empty($reservation->resched_date_in) ? $reservation->date_in : $reservation->resched_date_in;
                $date_out = empty($reservation->resched_date_out) ? $reservation->date_out : $reservation->resched_date_out;

                if ($date_in == $date_out) {
                    return Carbon::parse($date_out)->format('F j, Y') . ' - ' . '6:00 PM';
                } 

                return Carbon::parse($date_out)->format('F j, Y') . ' - ' . '12:00 PM';
            })

            ->add('rooms', function ($reservation) {
                return Blade::render('
                    <div class="max-w-[200px] flex gap-1 flex-wrap">
                        @foreach ($reservation->rooms as $room)
                            <div key="{{ $room->id }}" class="px-2 py-1 font-semibold capitalize rounded-md min-w-max bg-slate-100">
                                {{ $room->building->prefix . " " . $room->room_number }}
                            </div>
                        @endforeach
                    </div>
                ', ['reservation' =>$reservation]);
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
                ->sortable()
                ->searchable(),

            Column::make('Rooms', 'rooms'),

            Column::make('Check-in Date', 'date_in_formatted', 'date_in'),

            Column::make('Check-out Date', 'date_out_formatted', 'date_out'),

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

    public function checkOut(Reservation $reservation) {
        $service = new ReservationService;
        $service->checkOut($reservation);
        $this->toast('Success!', description: 'Guest checked-out');
        $this->dispatch('guest-checked-out');
        $this->dispatch('pg:eventRefresh-GuestTable');
    }
}
