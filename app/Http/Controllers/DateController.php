<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Support\Facades\Http;

class DateController extends Controller
{
    public static function today() {
        if (env('APP_ENV') == 'local') {
            return Carbon::createFromTimestamp($_SERVER['REQUEST_TIME'], env('APP_TIMEZONE'))->format('Y-m-d');
        }

        return Carbon::createFromTimestamp($_SERVER['REQUEST_TIME'], env('APP_TIMEZONE'))->addHours(8)->format('Y-m-d');
    }

    public static function tomorrow() {
        if (env('APP_ENV') == 'local') {
            return Carbon::createFromTimestamp($_SERVER['REQUEST_TIME'], env('APP_TIMEZONE'))->addDay()->format('Y-m-d');
        }

        return Carbon::createFromTimestamp($_SERVER['REQUEST_TIME'], env('APP_TIMEZONE'))->addHours(8)->addDay()->format('Y-m-d');
    }
}
