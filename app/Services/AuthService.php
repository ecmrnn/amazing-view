<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\User;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    use DispatchesToast;

    public function validatePassword($password) {
        if (Hash::check($password, Auth::user()->password)) {
            return true;
        } else {
            return false;
        }
    }

    public function passwordMatch(User $user, $password) {
        if (Hash::check($password, $user->password)) {
            return true;
        } else {
            return false;
        }
    }
}