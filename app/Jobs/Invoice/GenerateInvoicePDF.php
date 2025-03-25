<?php

namespace App\Jobs\Invoice;

use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateInvoicePDF implements ShouldQueue
{
    use Queueable;

    public $margin = [
        'top' => 48,
        'bottom' => 48,
        'right' => 48,
        'left' => 48,
    ];
    // public $headerView = 'pdf.invoices.header';
    // public $footerView = 'pdf.invoices.footer';
    public $path = '';
    public $filename = '';

    /**
     * Create a new job instance.
     */
    public function __construct(public Invoice $invoice)
    {
        $this->filename = $invoice->iid . ' - ' . strtoupper($invoice->reservation->user->last_name) . '_' . strtoupper($invoice->reservation->user->first_name) . '.pdf';
        $this->path = 'storage/app/public/pdf/invoice/' . $this->filename;
        logger($this->path);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Pdf::view('pdf.invoices.invoice_pdf', [
            'invoice' => $this->invoice
        ])
        ->format('letter')
        ->margins(
            $this->margin['top'],
            $this->margin['right'],
            $this->margin['bottom'],
            $this->margin['left'],
            Unit::Pixel
        )
        ->save($this->path);
    }
}
