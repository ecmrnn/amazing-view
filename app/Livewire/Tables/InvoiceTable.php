<?php

namespace App\Livewire\Tables;

use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
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

final class InvoiceTable extends PowerGridComponent
{
    use WithExport;

    #[Url] public $status;
    public string $primaryKey = 'invoices.id';
    public string $sortField = 'invoices.id';
    public string $sortDirection = 'desc';

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.invoice');
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
        if (isset($this->status)) {
            return Invoice::query()
                ->select([
                    'invoices.*',
                    'reservations.id',
                    'users.first_name',
                    'users.last_name',
                    'users.email'
                ])
                ->with('reservation')
                ->with('reservation.user')
                ->where('status', $this->status)
                ->join('reservations', 'reservations.id', '=', 'invoices.reservation_id')
                ->join('users', 'users.id', '=', 'reservations.user_id');
        }
        return Invoice::query()
            ->select([
                'invoices.*',
                'reservations.id',
                'users.first_name',
                'users.last_name',
                'users.email'
            ])
            ->with('reservation')
            ->with('reservation.user')
            ->join('reservations', 'reservations.id', '=', 'invoices.reservation_id')
            ->join('users', 'users.id', '=', 'reservations.user_id');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $invoice_statuses = ['Confirmed', 'Pending', 'Expired', 'Checked-in', 'Checked-out', 'Completed', 'Canceled'];

        return PowerGrid::fields()
            ->add('invoices.id')
            ->add('iid')
            ->add('iid_formatted', function ($invoice) {
                return Blade::render('
                    <div class="flex items-center gap-3">
                        <x-copy text="' . $invoice->iid . '" />
                        ' . $invoice->iid . '
                    </div>
                ');
            })

            ->add('name')
            ->add('name_formatted', function ($invoice) {
                return Blade::render('<span class="inline-block w-max">' . ucwords(strtolower($invoice->reservation->user->first_name)) . " " . ucwords(strtolower($invoice->reservation->user->last_name)) . '</span>');
            })

            ->add('email')
            ->add('email_formatted', function ($invoice) {
                return Blade::render('
                    <a class="text-blue-500 underline underline-offset-4" href="mailto:' . $invoice->reservation->user->email . '">' . $invoice->reservation->user->email . '</a>
                ');
            })

            ->add('issue_date')
            ->add('issue_date_formatted', function ($invoice) {
                if (!empty($invoice->issue_date)) {
                    return Blade::render('<span class="inline-block w-max">' . date_format(date_create($invoice->issue_date), 'F j, Y') . '</span>');
                } else {
                    return Blade::render('<span class="text-xs text-zinc-800/50">---</span>');
                }
            })

            ->add('due_date')
            ->add('due_date_formatted', function ($invoice) {
                return Blade::render('<span class="inline-block w-max">' . date_format(date_create($invoice->due_date), 'F j, Y') . '</span>');
            })

            ->add('status')
            ->add('status_formatted', function ($invoice) {
                return Blade::render('<x-status type="invoice" :status="' . $invoice->status . '" />');
            })

            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Invoice ID', 'iid_formatted', 'iid')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name_formatted', 'name')
                ->searchableRaw('CONCAT(`first_name`, " ", `last_name`)'),

            Column::make('Email', 'email_formatted', 'users.email')
                ->searchableRaw('email'),

            Column::make('Issue Date', 'issue_date_formatted', 'issue_date'),

            Column::make('Due Date', 'due_date_formatted', 'due_date'),

            Column::make('Status', 'status_formatted', 'status'),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('rid')
                ->placeholder('Reservation ID'),
        ];
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.invoice', [
            'row' => $row,
            'edit_link' => 'app.billings.edit',
            'view_link' => 'app.billings.show',
        ]);
    }
}
