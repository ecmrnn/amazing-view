<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ShowInvoice extends Component
{
    use DispatchesToast;
    
    protected $listeners = [
        'payment-added' => '$refresh',
        'payment-edited' => '$refresh',
        'payment-deleted' => '$refresh',
        'invoice-issued' => '$refresh',
    ];
    
    // Invoice
    public $invoice;
    public $remaining_days;
    public $payment_count = 0;

    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->remaining_days = (int) Carbon::parse(Carbon::now()->format('Y-m-d'))->diffInDays($invoice->due_date);
        $this->payment_count = $invoice->payments()->count();
    }

    public function download() {
        $billing = new BillingService;
        $pdf = $billing->downloadPdf($this->invoice);
        
        if (!$pdf) {
            $this->toast('Generating PDF', 'info', 'Please wait for a few seconds');
        } else {
            $this->toast('Downloading PDF', description: 'Stay online while we download your file!');
            return $pdf;
        }
    }

    public function render()
    {
        return view('livewire.app.invoice.show-invoice');
    }
}
