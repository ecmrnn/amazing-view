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
        $this->showCheckBox();

        return [
            // Exportable::make('export')
            //     ->striped()
            //     ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        if (isset($this->status)) {
            return Invoice::query()->where('status', $this->status)
                ->with('reservation')
                ->orderByDesc('created_at');
        }
        return Invoice::query()->with('reservation')
            ->orderByDesc('created_at');;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $invoice_statuses = ['Confirmed', 'Pending', 'Expired', 'Checked-in', 'Checked-out', 'Completed', 'Canceled'];

        return PowerGrid::fields()
            ->add('id')
            ->add('iid')

            ->add('name', fn($invoice) => e(ucwords(strtolower($invoice->reservation->first_name)) . ' ' . ucwords(strtolower($invoice->reservation->last_name))))

            ->add('email')
            ->add('email_formatted', function ($invoice) {
                return Blade::render('
                    <a class="text-blue-500 underline underline-offset-4" href="mailto:' . $invoice->reservation->email . '">' . $invoice->reservation->email . '</a>
                ');
            })

            ->add('issue_date')
            ->add('issue_date_formatted', function ($invoice) {
                if (!empty($invoice->issue_date)) {
                    return Carbon::parse($invoice->issue_date)->format('F j, Y');
                } else {
                    return Blade::render('<span class="text-xs text-zinc-800/50">---</span>');
                }
            })

            ->add('due_date')
            ->add('due_date_formatted', function ($invoice) {
                return Carbon::parse($invoice->due_date)->format('F j, Y');
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
            Column::make('Invoice ID', 'iid', 'iid')
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
        return view('components.table-actions.invoice', [
            'row' => $row,
            'edit_link' => 'app.billings.edit',
            'view_link' => 'app.billings.show',
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
