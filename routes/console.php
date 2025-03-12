<?php

use Illuminate\Support\Facades\Schedule;

// Expire Reservations every minute
Schedule::command('reservations:expire')
    ->everyMinute();

// Mark Reservations as 'No Show' every day at 12:00 AM
Schedule::command('reservations:no-show')
    ->daily();

// Resets OTP requests count to 0
Schedule::command('otp:reset-count')
    ->daily();

// Send Reservation Reminder Email every day at 2:00 PM
Schedule::command('reservations:send-reservation-reminder-email')
    ->dailyAt('17:00');