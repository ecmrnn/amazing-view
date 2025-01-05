<?php

namespace App\Http\Controllers\OTP;

use App\Http\Controllers\Controller;
use App\Mail\SendOtp;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailOtp extends Controller
{    
    public static function send($email) {
        // Generate OTP
        $otp = random_int(100000, 999999);

        // Save OTP to database
        $stored_otp = Otp::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]
        );
    
        // Send OTP to email
        // Mail::to($email)->queue(new SendOtp($stored_otp));ay

        return $otp;
    }

    public static function check($email, $otp) {
        $stored_otp = Otp::where('email', $email)->first();

        if ($stored_otp->otp == $otp && $stored_otp->expires_at > Carbon::now()) {
            return true;
        }

        return false;
    }
}
