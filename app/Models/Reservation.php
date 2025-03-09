<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function rules(array $excepts = []) {
        $rules = [
            'date_in' => 'required|date|after_or_equal:today',
            'date_out' => 'required|date|after_or_equal:date_in',
            'senior_count' => 'nullable|integer',
            'pwd_count' => 'nullable|integer',
            'adult_count' => 'required|integer|min:1',
            'children_count' => 'integer|min:0',
            'selected_rooms' => 'required',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required|digits:11|starts_with:09',
            'address' => 'required',
            'note' => 'nullable|max:200',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($rules[$field]);
            }
        } 

        return $rules;
    }

    public static function messages(array $excepts = []) {
        $messages = [
            'date_in.required' => 'Select a :attribute',
            'date_out.required_if' => 'Select a :attribute',
            'date_in.after_or_equal' => ':attribute must be after or equal to today',
            'date_out.after_or_equal' => ':attribute must be after or equal to check-in date',
            
            'adult_count.required' => 'Enter number of :attribute',
            'adult_count.min' => 'Minimum number of :attribute is 1',
            'children_count.min' => 'Minimum number of :attribute is 0',

            'first_name.required' => 'Enter a :attribute',
            'last_name.required' => 'Enter a :attribute',
            'first_name.min' => 'Minimum length of :attribute is 2',
            'last_name.min' => 'Minimum length of :attribute is 2',

            'selected_rooms.required' => 'Select a room first',

            'phone.required' => 'Enter a :attribute',
            'phone.min' => 'The length of :attribute must be 11',
            'phone.starts_with' => ':attribute must start with "09"',

            'address.required' => 'Enter an :attribute',
            
            'email.required' => 'Enter an :attribute',
            'email.email' => 'Enter a valid :attribute',

            'downpayment.required_unless' => 'Enter the amount of cash paid',
            'downpayment.min' => 'Minimum cash amount is 500.',
            'transaction_id.required_unless' => 'Transaction ID is required when payment method is online',
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
            'date_in' => 'Check-in Date',
            'date_out' => 'Check-out Date',
            'adult_count' => 'Adult',
            'children_count' => 'Children',
            'selected_rooms' => 'Room',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Contact Number',
            'email' => 'Email',
            'downpayment' => 'Cash',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($attributes[$field]);
            }
        } 

        return $attributes;
    }

    public function rooms(): BelongsToMany {
        return $this->belongsToMany(Room::class, 'room_reservations')->withPivot(['rate', 'status']);
    }

    public function roomsForReservation($reservation) {
        return $this->belongsToMany(Room::class, 'room_reservations')->where('room_reservations.reservation_id', $reservation);
    }

    public function user(): BelongsTo {
        return $this->BelongsTo(User::class);
    }

    public function services(): BelongsToMany {
        return $this->belongsToMany(AdditionalServices::class, 'additional_service_reservations')->withPivot('price');
    }

    public function invoice(): HasOne {
        return $this->hasOne(Invoice::class);
    }

    public function cars(): HasOneOrMany {
        return $this->hasMany(CarReservation::class);
    }

    public function cancelled(): HasOne {
        return $this->hasOne(CancelledReservation::class);
    }

    public function rescheduledFrom(): hasOne {
        return $this->hasOne(Reservation::class, 'rescheduled_to');
    }

    public function rescheduledTo(): HasOne {
        return $this->hasOne(Reservation::class, 'rescheduled_from');
    }

    public static function boot()
    {
        // Generate custom ID: https://laravelarticle.com/laravel-custom-id-generator
        parent::boot();

        self::creating(function ($reservation) {
            $reservation->rid = IdGenerator::generate([
                'table' => 'reservations',
                'field' => 'rid',
                'length' => 10,
                'prefix' => date('Rymd'),
                'reset_on_prefix_change' => true
            ]);

            $reservation->first_name = trim(strtolower($reservation->first_name));
            $reservation->last_name = trim(strtolower($reservation->last_name));
            $reservation->note = htmlentities(str_replace('"', "'", $reservation->note));
        });

        self::updating(function ($reservation) {
            $reservation->first_name = trim(strtolower($reservation->first_name));
            $reservation->last_name = trim(strtolower($reservation->last_name));
            $reservation->note = htmlentities(str_replace('"', "'", $reservation->note));

            if (empty($reservation->rid)) {
                $reservation->rid = IdGenerator::generate([
                    'table' => 'reservations',
                    'field' => 'rid',
                    'length' => 10,
                    'prefix' => date('Rymd'),
                    'reset_on_prefix_change' => true
                ]);
            }
        });

        self::deleting(function ($reservation) {
            $reservation->invoice()->delete();
        });
    }
}
