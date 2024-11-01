<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Content extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function rules(array $excepts = []) {
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'value' => 'nullable|max:255',
            'long_value' => 'nullable|max:1000',
        ];

        if (!empty($excepts)) {
            foreach ($excepts as $field) {
                unset($rules[$field]);
            }
        } 

        return $rules;
    }

    public function pages(): BelongsToMany {
        return $this->belongsToMany(Page::class, 'content_pages');
    }

    public static function boot() {
        parent::boot();
        
        Content::creating(function ($content) {
            $content->value = htmlentities(trim($content->value));
        });
    }
}
