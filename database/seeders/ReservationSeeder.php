<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomReservation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $reservations = Reservation::factory(10)->create();

        foreach ($reservations as $reservation) {
            $reservation->rooms()->attach($faker->numberBetween(1, 12));

            if ($reservation->date_in <= Carbon::now()->format('Y-m-d')) {
                $reservation->statuss = Reservation::STATUS_CHECKED_IN;

                foreach ($reservation->rooms as $room) {
                    $room->status = Room::STATUS_OCCUPIED;
                    $room->save();
                }
            }

            if ($reservation->status == Reservation::STATUS_CHECKED_OUT) {
                $downpayment = $faker->randomElement([500, 750, 1000]);
                $total_amount = 0;
        
                foreach ($reservation->rooms as $room) {
                    $total_amount += $room->rate;
                }
        
                $invoice = Invoice::create([
                    'reservation_id' => $reservation->id,
                    'total_amount' => $total_amount,
                    'issue_date' => $reservation->date_out,
                    'due_date' => Carbon::parse($reservation->date_out)->addWeek(),
                    'balance' => $total_amount - $downpayment,
                    'downpayment' => $downpayment            
                ]);
        
                InvoicePayment::create([
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $faker->randomNumber(4) . ' ' . $faker->randomNumber(4) . $faker->randomNumber(1),
                    'amount' => $downpayment,
                    'payment_date' => $reservation->date_out,
                    'payment_method' => $faker->randomElement(['cash', 'gcash', 'bank']),
                ]);
            }
        }
    }
}
