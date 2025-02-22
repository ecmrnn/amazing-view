<?php

namespace App\Livewire\App\Cards;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Livewire\Component;

class InvoiceCards extends Component
{
    public $total_invoice_amount;
    public $total_paid_amount;
    public $total_balance;
    public $overdue_invoices;

    public function render()
    {
        $this->total_invoice_amount = Invoice::sum('total_amount');
        $this->total_paid_amount = InvoicePayment::whereHas('invoice.reservation', function ($query) {
            $query->whereIn('status', [
                ReservationStatus::CONFIRMED,
                ReservationStatus::CHECKED_IN,
                ReservationStatus::CHECKED_OUT,
                ReservationStatus::COMPLETED,
            ]);
        })->sum('amount');
        $this->total_balance = Invoice::sum('balance');
        $this->overdue_invoices = Invoice::whereStatus(InvoiceStatus::DUE)->count();

        return view('livewire.app.cards.invoice-cards');
    }
}
