<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function rules(array $excepts = []) {
        $rules = [
            'name' => 'required|max:50',
            'type' => 'required',
            'description' => 'required|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'note' => 'nullable|max:50',
            'format' => 'required',
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
            'name.required' => 'Enter a :attribute',
            'name.max' => 'Maximum character is 50',
            
            'type.required' => 'Enter a :attribute',

            'description.required' => 'Enter a :attribute',
            'description.max' => 'Maximum character is 50',

            'start_date.required' => 'Enter a :attribute',
            'end_date.required' => 'Enter a :attribute',
            
            'note.max' => 'Maximum character is 50',
            'format.required' => 'Select a :attribute',
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
            'start_date' => 'start date',
            'end_date' => 'end date',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($attributes[$field]);
            }
        } 

        return $attributes;
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->rid = IdGenerator::generate([
                'table' => 'reports',
                'field' => 'rid',
                'length' => 12,
                'prefix' => date('Rymd'),
                'reset_on_prefix_change' => true
            ]);
        });
    }
}
