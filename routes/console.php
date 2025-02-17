<?php

use App\Console\Commands\UpdateExpiredReservations;
use Illuminate\Support\Facades\Schedule;

Schedule::command('reservations:expire')
    ->everyMinute();