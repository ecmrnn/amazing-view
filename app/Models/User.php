<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    public const ROLE_GUEST = 0;
    public const ROLE_FRONTDESK = 1;
    public const ROLE_ADMIN = 2;
    public const STATUS_ACTIVE = 0;
    public const STATUS_INACTIVE = 1;
    
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function rules(array $excepts = []) {
        $rules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|unique:users,email|email:rfc,dns',
            'phone' => 'required|digits:11|starts_with:09',
            'address' => 'nullable',
            'role' => 'required',
            'password' => 'required|string|min:8|confirmed',
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

            'last_name.required' => 'Enter a :attribute',
            'last_name.min' => 'Minimum length of :attribute is 2',

            'phone.required' => 'Enter a :attribute',
            'phone.min' => 'The length of :attribute must be 11',
            'phone.starts_with' => ':attribute must start with "09"',
            
            'email.required' => 'Enter an :attribute',
            'email.email' => 'Enter a valid :attribute',
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

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
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
