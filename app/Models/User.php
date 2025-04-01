<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\RateLimiter;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Validation\Rules\Password;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function rules(array $excepts = []) {
        $rules = [
            'first_name' => 'required|min:2|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\'\-]+$/u|max:255',
            'last_name' => 'required|min:2|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\'\-]+$/u|max:255',
            'email' => 'required|unique:users,email|email:rfc,dns',
            'phone' => 'required|digits:11|starts_with:09',
            'address' => 'nullable',
            'role' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($rules[$field]);
            }
        } 
        
        return $rules ;
    }

    public static function messages(array $excepts = []) {
        $messages = [
            'first_name.required' => 'Enter a :attribute',
            'first_name.min' => 'Minimum length of :attribute is 2',
            'first_name.regex' => 'Name can only contain letters, hyphens, and apostrophes',
            
            'last_name.required' => 'Enter a :attribute',
            'last_name.min' => 'Minimum length of :attribute is 2',
            'last_name.regex' => 'Name can only contain letters, hyphens, and apostrophes',

            'phone.required' => 'Enter a :attribute',
            'phone.min' => 'The length of :attribute must be 11',
            'phone.starts_with' => ':attribute must start with "09"',
            
            'email.required' => 'Enter an :attribute',
            'email.email' => 'Enter a valid :attribute',

            'password.required' => 'Enter your password',
            'password.confirmed' => 'Password confirmation mismatched',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($messages[$field]);
            }
        } 

        return $messages;

    }

    public static function validationAttributes(array $excepts = []) {
        $attributes = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Contact Number',
            'address' => 'Address',
            'email' => 'Email',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($attributes[$field]);
            }
        } 

        return $attributes;
    }

    public function reports(): HasMany {
        return $this->hasMany(Report::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function name() {
        return ucwords($this->first_name) . ' ' . ucwords($this->last_name);
    }

    public function role() {
        switch ($this->role) {
            case UserRole::GUEST->value:
                return 'Guest';
            case UserRole::RECEPTIONIST->value:
                return 'Receptionist';
            case UserRole::ADMIN->value:
                return 'Admin';
        }
    }

    public function announcements(): HasMany {
        return $this->hasMany(Announcement::class);
    }

    public static function boot()
    {
        // Generate custom ID: https://laravelarticle.com/laravel-custom-id-generator
        parent::boot();
        self::creating(function ($model) {
            $model->uid = IdGenerator::generate([
                'table' => 'users',
                'field' => 'uid',
                'length' => 10,
                'prefix' => 'U' . date('ymd'),
                'reset_on_prefix_change' => true
            ]);
        });

        self::creating(function ($user) {
            $user->first_name = trim(strtolower($user->first_name));
            $user->last_name = trim(strtolower($user->last_name));
        });

        self::updating(function ($user) {
            $user->first_name = trim(strtolower($user->first_name));
            $user->last_name = trim(strtolower($user->last_name));
        });

        self::deleting(function ($user) {
            $user->reports()->delete();
            foreach ($user->reservations as $reservation) {
                $reservation->delete();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
