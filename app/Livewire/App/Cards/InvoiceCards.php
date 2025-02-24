<?php

namespace App\Livewire\App\Cards;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Livewire\Component;

class InvoiceCards extends Component
{
    public $total_balance;
    public $pending_billing;
    public $partial_billing;
    public $overdue_billing;

    public function render()
    {
        $this->total_balance = Invoice::sum('balance');
        $this->pending_billing = Invoice::whereStatus(InvoiceStatus::PENDING)->count();
        $this->partial_billing = Invoice::whereStatus(InvoiceStatus::PARTIAL)->count();
        $this->overdue_billing = Invoice::whereStatus(InvoiceStatus::DUE)->count();

        return view('livewire.app.cards.invoice-cards');
    }
}
