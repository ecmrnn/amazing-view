<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserService
{
    public function create($data) {
        DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => $data['password'],
                'status' => UserStatus::ACTIVE,
            ]);

            // Assign permission to roles
            switch ($data['role']) {
                case UserRole::ADMIN->value:
                    $user->assignRole('admin');
                    break;
                case UserRole::RECEPTIONIST->value:
                    $user->assignRole('receptionist');
                    break;
                default:
                    $user->assignRole('guest');
                    break;
            }

            return $user;
        });
    }

    public function update(User $user, $data) {
        DB::transaction(function () use ($user, $data) {
            return $user->update($data);
        });
    }

    public function validatePassword($password) {
        $checks = [
            'min' => false,
            'uppercase' => false,
            'lowercase' => false,
            'numbers' => false,
            'symbols' => false,
        ];
    
        // Check for minimum length
        if (strlen($password) >= 8) {
            $checks['min'] = true;
        }
    
        // Check for uppercase letter
        if (preg_match('/[A-Z]/', $password)) {
            $checks['uppercase'] = true;
        }

        // Check for lowercase letter
        if (preg_match('/[a-z]/', $password)) {
            $checks['lowercase'] = true;
        }
    
        // Check for at least one number (0-9)
        if (preg_match('/\d/', $password)) {
            $checks['numbers'] = true;
        }
    
        // Check for at least one special character
        if (preg_match('/[\W_]/', $password)) { 
            $checks['symbols'] = true;
        }
    
        return $checks;
    }
    
    public function deactivate(User $user) {
        $this->forceLogout($user);
        
        DB::transaction(function () use ($user) {
            $user->update([
                'status' => UserStatus::INACTIVE,
            ]);

            foreach ($user->reservations as $reservation) {
                $reservation->update([
                    'status' => ReservationStatus::CANCELED,
                    'canceled_at' => now(),
                ]);

                $amenity = new AmenityService;
                $amenity->release($reservation, $reservation->rooms);

                $room = new RoomService;
                $room->release($reservation, $reservation->rooms);

                $reservation->invoice->update([
                    'status' => InvoiceStatus::CANCELED,
                ]);
            }

            return $user;
        });
    }

    public function activate(User $user) {
        DB::transaction(function () use ($user) {
            return $user->update([
                'status' => UserStatus::ACTIVE,
            ]);
        });
    }

    public function sendPasswordResetLink(Request $request, User $user) {
        /**
         *  This code is from a built-in controller
         * 
         *  @see \app\Http\Controllers\Auth\PasswordResetLinkController@store
         * */
        
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function forceLogout(User $user) {
        DB::table('sessions')->where('user_id', $user->id)->delete();
    }
}