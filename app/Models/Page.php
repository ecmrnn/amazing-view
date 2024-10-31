<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    use HasFactory;

    public const STATUS_UP = 0;
    public const STATUS_DOWN = 0;

    protected $guarded = [];

    public function medias(): BelongsToMany {
        return $this->belongsToMany(Media::class);
    }

    public function contents(): BelongsToMany {
        return $this->belongsToMany(Content::class, 'content_pages');
    }
}
