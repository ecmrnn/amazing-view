<?php

namespace App\Livewire\Tables;

use App\Models\InvoicePayment;
use Database\Factories\InvoicePaymentFactory;
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

final class InvoicePaymentTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'InvoicePaymentTable';
    public $invoice;

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
        return InvoicePayment::query()->whereInvoiceId($this->invoice->id);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('payment_method', fn($payment) => e(strtoupper($payment->payment_method)))
            ->add('transaction_id')
            ->add('amount', function ($payment) {
                return Blade::render('
                    <span><x-currency /> ' . number_format($payment->amount, 2) . '</span>
                ');
            })
            ->add('payment_date_formatted', fn ($payment) => Carbon::parse($payment->payment_date)->format('F j, Y'))
            ->add('proof_image_path')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            // Column::make('Id', 'id'),
            Column::make('Payment Method', 'payment_method'),

            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable(),

            Column::make('Payment date', 'payment_date_formatted', 'payment_date'),

            // Column::make('Proof image path', 'proof_image_path')
            //     ->sortable()
            //     ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('payment_method', 'payment_method')
                ->dataSource([
                    ['payment_method' => 'gcash', 'name' => 'GCASH'],
                    ['payment_method' => 'bank', 'name' => 'BANK'],
                ])
                ->optionLabel('name')
                ->optionValue('payment_method'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.invoice-payment', [
            'row' => $row,
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
