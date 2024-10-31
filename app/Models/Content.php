<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Content extends Model
{
    use HasFactory;

    protected $guarded = [];

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
