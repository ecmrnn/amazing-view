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

        $otp_record = Otp::where('email', $email)
            ->whereDate('created_at', now()->toDateString())
            ->first();
        
        // Save OTP to database
        if ($otp_record) {
            // Update existing record
            $otp_record->update([
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'request_count' => $otp_record->request_count + 1
            ]);
        } else {
            // Create new record
            Otp::create([
                'email' => $email,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'request_count' => 1
            ]);
        }

        $otp_record = Otp::where('email', $email)
            ->whereDate('created_at', now()->toDateString())
            ->first();
    
        // Send OTP to email
        Mail::to($email)->queue(new SendOtp($otp_record));

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
