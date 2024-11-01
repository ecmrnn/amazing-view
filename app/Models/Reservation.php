<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const STATUS_CONFIRMED = 0;
    public const STATUS_PENDING = 1;
    public const STATUS_EXPIRED = 2;
    public const STATUS_CHECKED_IN = 3;
    public const STATUS_CHECKED_OUT = 4;
    public const STATUS_COMPLETED = 5;
    public const STATUS_CANCELED = 6;

    public static function rules(array $excepts = []) {
        $rules = [
            'date_in' => 'required|date|after_or_equal:today',
            'date_out' => 'required|date|after_or_equal:date_in',
            'adult_count' => 'required|integer|min:1',
            'children_count' => 'integer|min:0',
            'selected_rooms' => 'required',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|digits:11|starts_with:09',
            'address' => 'required',
            'proof_image_path' => 'nullable|mimes:jpg,jpeg,png|file|max:1000|required_unless:payment_method,cash',
            'downpayment' => 'integer|min:500|required',
            'transaction_id' => 'nullable|string|required_unless:payment_method,cash',
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
            'date_out.required' => 'Select a :attribute',
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

            'proof_image_path.mimes' => 'Image format must be either of the following: JPG, JPEG, PNG',
            'proof_image_path.max' => 'File size must be less than 1000KB',
            'proof_image_path.required_unless' => 'Upload your payment slip',

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
            'proof_image_path' => 'Proof of Payment',
            'downpayment' => 'Cash',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($attributes[$field]);
            }
        } 

        return $attributes;
    }

    public static function computeBreakdown(Reservation $reservation) {
        $sub_total = 0;

        foreach ($reservation->rooms as $room) {
            $sub_total += $room->rate;
        }

        foreach ($reservation->amenities as $amenity) {
            $quantity = $amenity->pivot->quantity;
                        
            // If quantity is 0, change it to 1
            $quantity != 0 ?: $quantity = 1;

            $sub_total += ($amenity->price * $quantity);
        }
        
        // dd($reservation->amenities);
        $vatable_sales = $sub_total / 1.12;
        $vat = ($sub_total) - $vatable_sales;
        $net_total = $vatable_sales + $vat;

        return [
            'vatable_sales' => $vatable_sales,
            'vat' => $vat,
            'net_total' => $net_total,
        ];
    }

    public function rooms(): BelongsToMany {
        return $this->BelongsToMany(Room::class, 'room_reservations');
    }

    public function user(): BelongsTo {
        return $this->BelongsTo(User::class);
    }

    public function amenities(): BelongsToMany {
        return $this->belongsToMany(Amenity::class, 'reservation_amenities')->withPivot('quantity');
    }

    public function invoice(): HasOne {
        return $this->hasOne(Invoice::class);
    }

    public static function boot()
    {
        // Generate custom ID: https://laravelarticle.com/laravel-custom-id-generator
        parent::boot();
        self::creating(function ($model) {
            $model->rid = IdGenerator::generate([
                'table' => 'reservations',
                'field' => 'rid',
                'length' => 12,
                'prefix' => date('Rymd'),
                'reset_on_prefix_change' => true
            ]);
        });

        Reservation::creating(function ($reservation) {
            $reservation->first_name = trim(strtolower($reservation->first_name));
            $reservation->last_name = trim(strtolower($reservation->last_name));
            $reservation->note = htmlentities(str_replace('"', "'", $reservation->note));
        });

        Reservation::updating(function ($reservation) {
            $reservation->first_name = trim(strtolower($reservation->first_name));
            $reservation->last_name = trim(strtolower($reservation->last_name));
            $reservation->note = htmlentities(str_replace('"', "'", $reservation->note));
        });
    }
}
