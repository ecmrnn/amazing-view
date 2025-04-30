<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Http\Controllers\DateController;
use App\Models\Reservation;
use App\Services\BillingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ApplyLateCheckOutFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:apply-late-check-out-fee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Applies late check-out fee on checked-in reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = DateController::today();
        $time = DateController::time();

        $late_reservations = Reservation::whereStatus(ReservationStatus::CHECKED_IN->value)
            ->whereRaw('CONCAT(date_out, " ", time_out) < ?', $date . ' ' . $time)
            ->get();

        foreach ($late_reservations as $reservation) {
            $date_out = $reservation->date_out . ' ' . $reservation->time_out;

            // Get the rooms not checked-out
            foreach ($reservation->rooms as $room) {
                if ($room->pivot->status == ReservationStatus::CHECKED_IN->value) {
                    $hours = floor(Carbon::parse($date_out)->diffInHours(Carbon::parse($date . ' ' . $time)));

                    if ($hours > 0) {
                        $price = 100;
    
                        $reservation->invoice->items()->updateOrCreate(
                            [
                                'invoice_id' => $reservation->invoice->id,
                                'room_id' => $room->id,
                                'name' => 'Fee: Late check-out',
                            ],[
                                'quantity' => $hours,
                                'price' => $price, /* Gagawin sa settings */
                                'total' => $hours * $price,
                            ]);
                    }
                }
            }

            // Update invoice
            $billing = new BillingService;
            $taxes = $billing->taxes($reservation);
            $payments = $reservation->invoice->payments->sum('amount');

            $reservation->invoice->sub_total = $taxes['sub_total'];
            $reservation->invoice->total_amount = $taxes['net_total'];
            $reservation->invoice->balance = $taxes['net_total'] - $payments;
            $reservation->invoice->save();
        }
    }
}
