<?php

use App\Models\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->time('time_in')->after('date_in');
            $table->time('time_out')->after('date_out');
        });

        // Update records directly using the DB facade
        DB::table('reservations')->get()->each(function ($reservation) {
            $time_in = '';
            $time_out = '';

            if ($reservation->date_in == $reservation->date_out) {
                $time_in = '08:00:00';
                $time_out = '18:00:00';
            } else {
                $time_in = '14:00:00';
                $time_out = '12:00:00';
            }

            DB::table('reservations')->where('id', $reservation->id)->update([
                'time_in' => $time_in,
                'time_out' => $time_out,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
