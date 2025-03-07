<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateNoShowReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:no-show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update reservations that is past their check-in date without being finalized.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reservations = Reservation::whereStatus(ReservationStatus::CONFIRMED)
            ->where('date_in', '<', Carbon::now()->format('Y-m-d'))
            ->get();

        if ($reservations->count() > 0) {
            $service = new ReservationService;
            foreach ($reservations as $reservation) {
                $service->noShow($reservation);
            }
        }
    }
}
