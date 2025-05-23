<?php

namespace App\Livewire\Tables;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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

final class GuestReservationTable extends PowerGridComponent
{
    use WithExport;

    public $user;

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.reservations');
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
        return Reservation::query()->whereBelongsTo($this->user)->orderByDesc('id');
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

            ->add('date_in')
            ->add('date_in_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_in)->format('F j, Y');
            })

            ->add('date_out')
            ->add('date_out_formatted', function ($reservation) {
                return Carbon::parse($reservation->date_out)->format('F j, Y');
            })

            ->add('status_formatted', function ($reservation) {
                return Blade::render('<x-status type="reservation" :status="' . $reservation->status . '" />');
            })

            ->add('note')
            ->add('note_formatted', function ($reservation) {
                return Blade::render(
                    '<x-tooltip :textWrap="false" text="' . html_entity_decode($reservation->note)  . '" dir="top">
                        <div x-ref="content" class="max-w-[250px] line-clamp-1">' . html_entity_decode($reservation->note) . '</div>
                    </x-tooltip>'
                );
            })

            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Reservation ID', 'rid')
                ->sortable()
                ->searchable(),

            Column::make('Check-in Date', 'date_in_formatted', 'date_in'),
            Column::make('Check-out Date', 'date_out_formatted', 'date_out'),
            Column::make('Note', 'note_formatted', 'note'),

            Column::make('Status', 'status_formatted', 'status'),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actionsFromView($row)
    {
        $user = Auth::user();
        
        $view_link = $user->hasRole('guest') ? 'app.reservations.show-guest-reservations' : 'app.reservations.show';
        
        return view('components.table-actions.reservation', [
            'row' => $row,
            'edit_link' => 'app.reservations.edit',
            'view_link' => $view_link,
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
