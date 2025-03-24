<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

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
            'image.required' => 'Upload an image of your milestone',
            'title.required' => 'Enter a title',
            'description.required' => 'Enter a description',
            'description.max' => 'Maximum of 200 characters',
            'date_achieved.required' => 'Enter a date you achieve this milestone',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($rules[$field]);
            }
        } 

        return $rules;
    }
}
