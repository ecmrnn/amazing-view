<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Support\Facades\Http;

class DateController extends Controller
{
    public static function today() {
        return Carbon::parse($_SERVER['REQUEST_TIME'])->addDay()->format('Y-m-d');
    }

    public static function tomorrow() {
        return Carbon::parse($_SERVER['REQUEST_TIME'])->addDays(2)->format('Y-m-d');
    }
}
