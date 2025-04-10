<?php

namespace App\Jobs\Invoice;

use App\Events\InvoicePDFGenerated;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;
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
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Pdf::view('pdf.invoices.invoice_pdf', [
            'invoice' => $this->invoice
        ])
        // ->withBrowsershot(function (Browsershot $browsershot) {
        //     // prevents puppeteer errors with navigation
        //     $browsershot->noSandbox()
        //         ->setChromePath('/usr/bin/chromium-browser');
        // })
        ->format('letter')
        ->margins(
            $this->margin['top'],
            $this->margin['right'],
            $this->margin['bottom'],
            $this->margin['left'],
            Unit::Pixel
        )
        ->save($this->path);
        
        broadcast(new InvoicePDFGenerated($this->invoice));
    }
}
