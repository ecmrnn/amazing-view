<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Mail\reservation\Expire;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpdateExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update the status of expired reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new ReservationService;
        $expired_reservations = Reservation::where('status', ReservationStatus::AWAITING_PAYMENT)
            ->where('expires_at', '<', now())
            ->get();

        if ($expired_reservations->count() > 0) {
            foreach ($expired_reservations as $reservation) {
                $service->expire($reservation);
            }
        }
    }
}
