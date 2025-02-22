<?php

namespace App\Livewire\Tables;

use App\Enums\InvoiceStatus;
use App\Models\InvoicePayment;
use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Database\Factories\InvoicePaymentFactory;
use Hamcrest\Description;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
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
use Livewire\Attributes\Validate;

final class InvoicePaymentTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'InvoicePaymentTable';
    public $invoice;
    #[Validate] public $amount;
    #[Validate] public $payment_date;
    #[Validate] public $transaction_id;
    public $payment_method;
    #[Validate] public $password;

    public function rules() {
        return [
            'amount' => 'required|gt:0',
            'payment_date' => 'required|date',
            'password' => 'required',
            'transaction_id' => 'nullable|required_unless:payment_method,cash',
        ];
    }

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
        return InvoicePayment::query()->where('invoice_id', $this->invoice->id);
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
            ->add('transaction_id_formatted', function ($payment) {
                if ($payment->payment_method === 'cash') {
                    return Blade::render('<span class="text-xs text-zinc-800/50">Not Available</span>');
                }

                return Blade::render('<span>' . $payment->transaction_id . '</span>');
            })
            ->add('amount')
            ->add('amount_formatted', function ($payment) {
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

            Column::make('Reference ID', 'transaction_id_formatted', 'transaction_id')
                ->sortable()
                ->searchable(),

            Column::make('Amount', 'amount_formatted', 'amount')
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
                    ['payment_method' => 'cash', 'name' => 'CASH'],
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

    #[On('edit-payment')]
    public function editPayment($id, $amount, $payment_date, $transaction_id, $payment_method) {
        $this->amount = $amount;
        $this->payment_date = $payment_date;
        $this->transaction_id = $transaction_id;
        $this->payment_method = strtolower($payment_method);

        $this->validate([
            'amount' => $this->rules()['amount'],
            'payment_date' => $this->rules()['payment_date'],
            'transaction_id' => $this->rules()['transaction_id'],
        ]);

        $payment = InvoicePayment::find($id);
        
        if ($this->amount > $payment->amount && ($this->amount - $payment->amount) > $payment->invoice->balance) {
            $this->addError('amount', 'Amount must not exceed ' . number_format(abs($payment->invoice->balance + $payment->amount), 2));
            return;
        }

        $difference = $payment->amount - $this->amount;
        $payment->amount = $this->amount;
        $payment->payment_date = $this->payment_date;
        $payment->transaction_id = $this->transaction_id;
        $payment->invoice->balance += $difference;

        if ($payment->invoice->balance == 0) {
            $payment->invoice->status = InvoiceStatus::PAID;
        } else {
            $payment->invoice->status = InvoiceStatus::PARTIAL;
        }

        $payment->invoice->save();
        $payment->save();

        $this->dispatch('payment-edited');
        $this->dispatch('pg:eventRefresh-InvoicePaymentTable');
        $this->toast('Success!', description: 'Payment edited!');
    }

    #[On('delete-payment')]
    public function deletePayment(InvoicePayment $payment) {
        $this->validate([
            'password' => 'required',
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $payment->invoice->balance += $payment->amount;
            $payment->invoice->save();
    
            $payment->delete();
            $this->dispatch('pg:eventRefresh-InvoicePaymentTable');
            $this->toast('Success!', description: 'Payment deleted!');
            $this->dispatch('payment-deleted');
            $this->reset('password');
        } else {
            $this->addError('password', 'Password mismatch, try again');
        }
    }
}
