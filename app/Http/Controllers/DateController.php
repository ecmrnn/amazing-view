<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class DateController extends Controller
{
    public static function today() {
        try {
            $response = Http::get('http://api.timezonedb.com/v2.1/get-time-zone?key=3IGL257P8RYO&format=json&by=zone&zone=Asia/Manila');
    
            if ($response->successful()) {
                $result = $response->json();
    
                $date = Carbon::parse($result['formatted'])->format('Y-m-d');
                return $date;
            }
        } catch (\Throwable $th) {
            logger('Method: today(), TimezoneDB is not responding properly: https://timezonedb.com/');
        }
    }

    public static function tomorrow() {
        try {
            $response = Http::get('http://api.timezonedb.com/v2.1/get-time-zone?key=3IGL257P8RYO&format=json&by=zone&zone=Asia/Manila');
    
            if ($response->successful()) {
                $result = $response->json();
    
                $date = Carbon::parse($result['formatted'])->addDay()->format('Y-m-d');
                return $date;
            }
        } catch (\Throwable $th) {
            logger('Method: tomorrow(), TimezoneDB is not responding properly: https://timezonedb.com/');
        }
    }
}
