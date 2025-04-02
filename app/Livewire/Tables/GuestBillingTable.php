<?php

namespace App\Livewire\Tables;

use App\Models\Invoice;
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

final class GuestBillingTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'GuestBillingTable';
    public string $sortField = 'invoices.id';

    public $user;

    public function setUp(): array
    {
        return [
            Header::make()
                ->withoutLoading()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Invoice::query()
            ->select('invoices.*')
            ->where('reservations.user_id', $this->user->id)
            ->join('reservations', 'reservations.id', '=', 'invoices.reservation_id');
    }

    public function fields(): PowerGridFields
    {
        $invoice_statuses = ['Confirmed', 'Pending', 'Expired', 'Checked-in', 'Checked-out', 'Completed', 'Canceled'];

        return PowerGrid::fields()
            ->add('id')
            ->add('iid')
            ->add('iid_formatted', function ($invoice) {
                return Blade::render('
                    <div class="flex items-center gap-3">
                        <x-copy text="' . $invoice->iid . '" />
                        ' . $invoice->iid . '
                    </div>
                ');
            })

            ->add('name', function ($invoice) {
                return Blade::render('<span class="inline-block w-max">' . ucwords(strtolower($invoice->reservation->user->first_name)) . ' ' . ucwords(strtolower($invoice->reservation->user->last_name)) . '</span>');
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
            Column::make('Name', 'name'),

            Column::make('Email', 'email_formatted', 'email')
                ->searchable(),

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
        $view_link = $row->reservation->user->hasRole('guest') ? 'app.billings.show-guest-billings' : 'app.billings.show';

        return view('components.table-actions.invoice', [
            'row' => $row,
            'edit_link' => 'app.billings.edit',
            'view_link' => $view_link,
        ]);
    }
}
