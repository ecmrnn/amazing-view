<?php

namespace App\Jobs\Reservation;

use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateReservationPDF implements ShouldQueue
{
    use Queueable;

    public $margin = [
        'top' => 48,
        'bottom' => 48,
        'right' => 48,
        'left' => 48,
    ];
    public $path = '';
    public $filename = '';

    /**
     * Create a new job instance.
     */
    public function __construct(public Reservation $reservation)
    {
        $this->filename = $reservation->rid . ' - ' . strtoupper($reservation->user->last_name) . '_' . strtoupper($reservation->user->first_name) . '.pdf';
        $this->path = 'app/public/pdf/reservation/' . $this->filename;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        return Pdf::view('pdf.reservations.reservation_pdf', [
            'reservation' => $this->reservation
        ])
        ->withBrowsershot(function (Browsershot $browsershot) {
            $browsershot->noSandbox();
        })
        ->format('letter')
        ->margins(
            $this->margin['top'],
            $this->margin['right'],
            $this->margin['bottom'],
            $this->margin['left'],
            Unit::Pixel
        )
        ->save(storage_path($this->path));
    }
}
