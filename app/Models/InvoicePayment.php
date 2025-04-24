<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function rules(array $excepts = []) {
        $rules = [
            'payment_date' => 'required|date',
            'payment_method' => 'required',
            'proof_image_path' => 'nullable|mimes:jpg,jpeg,png|file|max:1000|required_unless:payment_method,cash',
            'transaction_id' => 'required_unless:payment_method,cash|regex:/^[A-Za-z0-9 ]+$/',
            'purpose' => 'required',
            'amount' => 'required|integer|min:1',
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
            'payment_date.required' => 'Date of payment is required',

            'payment_method.required' => 'Select a payment method',

            'proof_image_path.mimes' => 'Image format must be either of the following: JPG, JPEG, PNG',
            'proof_image_path.max' => 'File size must be less than 1000KB',
            'proof_image_path.required_unless' => 'Upload your payment slip',

            'transaction_id.required_unless' => 'Transaction ID is required when payment method is online',
            'amount.required' => 'Enter the amount of cash paid',
            'amount.min' => 'Cash paid must be greater than zero',
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

    public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class);
    }

    public static function boot()
    {
        // Generate custom ID: https://laravelarticle.com/laravel-custom-id-generator
        parent::boot();
        self::creating(function ($payment) {
            $payment->orid = IdGenerator::generate([
                'table' => 'invoice_payments',
                'field' => 'orid',
                'length' => 12,
                'prefix' => 'OR' . date('ymd'),
                'reset_on_prefix_change' => true
            ]);
        });

        self::updating(function ($payment) {
            if (empty($payment->orid)) {
                $payment->orid = IdGenerator::generate([
                    'table' => 'invoice_payments',
                    'field' => 'orid',
                    'length' => 12,
                    'prefix' => 'OR' . date('ymd'),
                    'reset_on_prefix_change' => true
                ]);
            }
        });
    }
}
