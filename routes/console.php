<?php

use App\Jobs\Reservation\GenerateIncomingReservation;
use Illuminate\Support\Facades\Schedule;

/** 
 * Reservation commands
 */

// Expire Reservations every minute
Schedule::command('reservations:expire')
    ->everyMinute();

// Mark Reservations as 'No Show' every day at 12:00 AM
Schedule::command('reservations:no-show')
    ->daily();

// Send Reservation Reminder Email every day at 2:00 PM
Schedule::command('reservations:send-reservation-reminder-email')
    ->dailyAt('17:00');

Schedule::job(new GenerateIncomingReservation)
    ->everyFiveMinutes();

/** 
 * Other operational commands
 */

// Resets OTP requests count to 0
Schedule::command('otp:reset-count')
    ->daily();

// Mark Expired Announcements as 'Expired' every day at 12:00 AM
Schedule::command('annoucement:expire')
    ->daily();

// Disable Expired Promo every day at 12:00 AM
Schedule::command('promo:expired')
    ->daily();
