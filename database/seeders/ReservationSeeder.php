<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Reservation;
use App\Models\RoomReservation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservation = Reservation::create([
            'date_in' => Carbon::now()->format('Y-m-d'),
            'date_out' => Carbon::now()->addDay()->format('Y-m-d'),
            'adult_count' => 2,
            'children_count' => 0,
            'status' => Reservation::STATUS_PENDING,

            'first_name' => 'Ec',
            'last_name' => 'Maranan',
            'phone' => '09262355376',
            'address' => '410 Manila East Rd., Hulo, Pililla, Rizal',
            'email' => 'marananemanuelle@test.com',
            'note' => 'This is a seeded reservation 🎉'
        ]);

        $rooms = array(1, 2, 3);
        
        foreach ($rooms as $room) {
            $reservation->rooms()->attach($room);
        }

        $downpayment = 500;
        $total_amount = 0;

        foreach ($reservation->rooms as $room) {
            $total_amount += $room->rate;
        }

        $invoice = Invoice::create([
            'reservation_id' => $reservation->id,
            'total_amount' => $total_amount,
            'balance' => $total_amount - $downpayment,
            'downpayment' => $downpayment            
        ]);

        InvoicePayment::create([
            'invoice_id' => $invoice->id,
            'transaction_id' => '0143 0141 1',
            'amount' => $downpayment,
            'payment_date' => Carbon::now(),
            'payment_method' => 'gcash',
        ]);
    }
}
