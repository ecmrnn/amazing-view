<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShowInvoices extends Component
{
    public $invoice_count;
    public $reservation_by_status = [
        'all' => 0,
        'partial' => 0,
        'paid' => 0,
        'pending' => 0,
        'due' => 0,
    ];
    #[Url] public $status;
    
    public function render()
    {
        $this->invoice_count = Invoice::count();
        return view('livewire.app.invoice.show-invoices');
    }
}
