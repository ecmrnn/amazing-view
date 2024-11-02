<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 0;
    public const STATUS_INACTIVE = 1;

    protected $guarded = [];

    public static function rules() {
        $rules = [
            'image' => 'nullable|mimes:jpg,jpeg,png|image',
            'title' => 'required',
            'description' => 'required|max:200',
            'date_achieved' => 'required|date'
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($rules[$field]);
            }
        } 

        return $rules;
    }

    public static function messages() {
        $rules = [
            // 'image.max' => 'nullable|mimes:jpg,jpeg,png|image|max:1000',
            // 'image.required' => 'nullable|mimes:jpg,jpeg,png|image|max:1000',
            'title.required' => 'Enter a title',
            'description.required' => 'Enter a description',
            'description.max' => 'Maximum of 200 characters',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($rules[$field]);
            }
        } 

        return $rules;
    }
}
