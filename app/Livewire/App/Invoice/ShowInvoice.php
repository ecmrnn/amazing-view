<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ShowInvoice extends Component
{
    protected $listeners = [
        'payment-added' => '$refresh',
        'payment-edited' => '$refresh',
        'payment-deleted' => '$refresh',
    ];
    
    // Invoice
    public $invoice;
    public $remaining_days;

    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->remaining_days = (int) Carbon::parse(Carbon::now()->format('Y-m-d'))->diffInDays($invoice->due_date);
    }

    public function render()
    {
        return view('livewire.app.invoice.show-invoice');
    }
}
