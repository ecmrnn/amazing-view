<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowInvoice extends Component
{
    use DispatchesToast;
    
    public function getListeners() {
        return [
            'echo-private:invoices.' . $this->invoice->id . ',InvoicePDFGenerated' => 'download',
            'payment-added' => '$refresh',
            'payment-edited' => '$refresh',
            'payment-deleted' => '$refresh',
            'invoice-issued' => '$refresh',
        ];
    }
    
    // Invoice
    public $invoice;
    public $remaining_days;
    public $payment_count = 0;

    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->remaining_days = (int) Carbon::parse(Carbon::now()->format('Y-m-d'))->diffInDays($invoice->due_date);
        $this->payment_count = $invoice->payments()->count();
    }

    public function printBill() {
        $billing = new BillingService;
        $billing->printBill($this->invoice);
        $this->toast('Generating PDF', 'info', 'Please wait for a few seconds');
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
