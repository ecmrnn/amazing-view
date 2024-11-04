<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function rules(array $excepts = []) {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'min_rate' => 'required|numeric',
            'max_rate' => 'required|numeric',
            'image_1_path' => 'nullable|image|mimes:jpg,jpeg,png',
            'image_2_path' => 'nullable|image|mimes:jpg,jpeg,png',
            'image_3_path' => 'nullable|image|mimes:jpg,jpeg,png',
            'image_4_path' => 'nullable|image|mimes:jpg,jpeg,png',
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
            'name.required' => 'Enter a room name',
            'description.required' => 'Enter a description',
            'min_rate.required' => 'Enter a minimum room rate',
            'max_rate.required' => 'Enter a maximum room rate',

            'image_1_path.required' => 'Upload a thumbnail',
            'image_1_path.mimes' => 'Image must be JPG, JPEG, or PNG',
            'image_2_path.required' => 'Upload an image',
            'image_2_path.mimes' => 'Image must be JPG, JPEG, or PNG',
            'image_3_path.required' => 'Upload an image',
            'image_3_path.mimes' => 'Image must be JPG, JPEG, or PNG',
            'image_4_path.required' => 'Upload an image',
            'image_4_path.mimes' => 'Image must be JPG, JPEG, or PNG',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($messages[$field]);
            }
        } 

        return $messages;

    }

    public function rooms(): HasMany {
        return $this->hasMany(Room::class);
    }
}
