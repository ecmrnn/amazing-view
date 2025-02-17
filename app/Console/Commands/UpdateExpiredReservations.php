<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $expired_reservations = Reservation::where('status', ReservationStatus::AWAITING_PAYMENT)
            ->where('expires_at', '<', now())
            ->update([
                'status' => ReservationStatus::EXPIRED
            ]);

        logger('Expired reservations updated');
    }
}
