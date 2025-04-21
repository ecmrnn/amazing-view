<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Support\Facades\Http;

class DateController extends Controller
{
    public static function today() {
        return Carbon::createFromTimestamp($_SERVER['REQUEST_TIME'], env('APP_TIMEZONE'))->format('Y-m-d');
    }

    public static function tomorrow() {
        return Carbon::createFromTimestamp($_SERVER['REQUEST_TIME'], env('APP_TIMEZONE'))->addDay()->format('Y-m-d');
    }
}
