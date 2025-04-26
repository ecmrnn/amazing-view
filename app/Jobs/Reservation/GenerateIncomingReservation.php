<?php

namespace App\Jobs\Reservation;

use App\Enums\ReportType;
use App\Enums\ReservationStatus;
use App\Http\Controllers\DateController;
use App\Mail\Reservation\SendReservationsTomorrow;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateIncomingReservation implements ShouldQueue
{
    use Queueable;

    public $margin = [
        'top' => 112,
        'bottom' => 112,
        'right' => 48,
        'left' => 48,
    ];
    public $headerView = 'pdf.reports.header';
    public $footerView = 'pdf.reports.footer';
    public $path = '';
    public $filename = '';
    public $report;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->report = Report::create([
            'name' => 'System - Incoming Reservations ' . Carbon::parse(DateController::tomorrow())->format('Y-m-d'),
            'type' => ReportType::INCOMING_RESERVATIONS->value,
            'format' => 'pdf',
            'start_date' => DateController::tomorrow(),
        ]);

        $this->report->path = $this->report->format . '/report/' . $this->report->name . ' - ' . $this->report->rid . '.' . $this->report->format;
        $this->report->save();

        $this->filename = $this->report->name . ' - ' . $this->report->rid . '.' . $this->report->format;
        $this->path = storage_path('app/public/' . $this->report->format . '/report/' . $this->filename);
        
        $reservations = Reservation::whereDate('date_in', $this->report->start_date)
            ->whereStatus(ReservationStatus::CONFIRMED->value)
            ->get();
        $guest_count = Reservation::selectRaw('sum(adult_count) as total_adults, sum(children_count) as total_children')
            ->whereDate('date_in', $this->report->start_date)
            ->whereStatus(ReservationStatus::CONFIRMED->value)
            ->first();
            
        Pdf::view('pdf.reports.incoming_reservations', [
            'reservations' => $reservations,
            'report' => $this->report,
            'guest_count' => $guest_count,
        ])
        ->withBrowsershot(function (Browsershot $browsershot) {
            $browsershot->noSandbox();
        })
        ->format('letter')
        ->orientation(Orientation::Landscape)
        ->margins(
            $this->margin['top'],
            $this->margin['right'],
            $this->margin['bottom'],
            $this->margin['left'],
            Unit::Pixel)
        ->headerView($this->headerView, [
            'report' => $this->report
        ])
        ->footerView($this->footerView, [
            'report' => $this->report
        ])
        ->save($this->path);

        // Send Email
        $site_email = Settings::where('key', 'site_email')->pluck('value')->first();

        // Send email
        Mail::to($site_email)->queue(new SendReservationsTomorrow($this->report->fresh()));
    }
}
