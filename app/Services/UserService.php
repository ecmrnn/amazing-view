<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            
            $roles = ['guest', 'receptionist', 'admin'];

            $role = $roles[$data['role'] - 1];

            if ($user->role != $data['role']) {
                $user->assignRole($role);
            }

            $user->update($data);
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
    
    public function deactivate(User $user, $cancel_reservations) {
        return DB::transaction(function () use ($user, $cancel_reservations) {
            $this->forceLogout($user);

            $user->update([
                'status' => UserStatus::INACTIVE,
            ]);

            if ($cancel_reservations) {
                $reservations = $user->reservations()->whereIn('status', [
                    ReservationStatus::AWAITING_PAYMENT,
                    ReservationStatus::PENDING,
                    ReservationStatus::CONFIRMED,
                ])->get();
               
                foreach ($reservations as $reservation) {
                    $service = new ReservationService;

                    $canceled_by = Auth::user()->hasRole('guest') ? 'guest' : 'management';
                    $refund_amount = $service->calculateRefundAmount($reservation);

                    $cancelation_details = [
                        'reason' => 'Account deactivated',
                        'canceled_by' => $canceled_by,
                        'refund_amount' => $refund_amount
                    ];

                    $service->cancel($reservation, $cancelation_details);
                }
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