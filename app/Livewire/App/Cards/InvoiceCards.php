<?php

namespace App\Livewire\App\Cards;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Livewire\Component;

class InvoiceCards extends Component
{
    public $total_balance;
    public $pending_billing;
    public $partial_billing;
    public $total_refund;

    public function render()
    {
        $this->total_balance = ceil(Invoice::whereIn('status', [InvoiceStatus::PENDING, InvoiceStatus::PARTIAL, InvoiceStatus::DUE])->where('balance', '>', 0)->sum('balance'));
        $this->total_refund = abs(ceil(Invoice::whereIn('status', [InvoiceStatus::PENDING, InvoiceStatus::PARTIAL, InvoiceStatus::DUE])->where('balance', '<', 0)->sum('balance')));
        $this->pending_billing = Invoice::whereStatus(InvoiceStatus::PENDING)->count();
        $this->partial_billing = Invoice::whereStatus(InvoiceStatus::PARTIAL)->count();

        return view('livewire.app.cards.invoice-cards');
    }
}
