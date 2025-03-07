<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Mail\Reservation\Reminder;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReservationReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reservation-reminder-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reservations = Reservation::where('status', ReservationStatus::CONFIRMED)
            ->where('date_in', Carbon::tomorrow()->format('Y-m-d'))
            ->get();
        
        if ($reservations->count() > 0) {
            foreach ($reservations as $reservation) {
                // Mail::to($reservation->email)->queue(new Reminder($reservation));
            }
        }
    }
}
