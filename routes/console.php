<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('reservations:expire')
    ->everyMinute();

Schedule::command('reservations:no-show')
    ->daily();