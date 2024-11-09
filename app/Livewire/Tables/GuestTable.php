<?php

namespace App\Livewire\Tables;

use App\Models\Reservation;
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
    use WithExport;

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
                        ->whereStatus(Reservation::STATUS_CHECKED_IN);
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
                if ($reservation->date_out == Carbon::now()->format('Y-m-d')) {
                    return Blade::render('<div class="flex items-center gap-3"><div class="w-2 bg-red-500 rounded-full aspect-square"></div>' . $reservation->rid . '</div>');
                }
                return Blade::render('<div class="flex items-center gap-3"><div class="w-2 bg-green-500 rounded-full aspect-square"></div>' . $reservation->rid . '</div>');
            })

            ->add('first_name')
            ->add('last_name')
            ->add('name_formatted', function($reservation) {
                return ucwords(strtolower($reservation->first_name)) . ' ' . ucwords(strtolower($reservation->last_name));
            })


            ->add('date_in')
            ->add('date_in_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_in)->format('F j, Y'); //20/01/2024 10:05
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_out)->format('F j, Y'); //20/01/2024 10:05
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

            ->add('note')
            ->add('note_formatted', function ($reservation) {
                return Blade::render(
                    '<x-tooltip :textWrap="false" text="' . $reservation->note . '" dir="top">
                        <div x-ref="content" class="max-w-[250px] line-clamp-1">' . $reservation->note . '</div>
                    </x-tooltip>'
                );
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

            Column::make('Check in', 'date_in_formatted', 'date_in'),

            Column::make('Check out', 'date_out_formatted', 'date_out'),

            Column::make('Note', 'note_formatted', 'note'),

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
            'edit_link' => 'app.guests.edit',
            'view_link' => 'app.guests.show',
        ]);
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
