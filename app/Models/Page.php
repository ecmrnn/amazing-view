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
    public const PAGE_HOME = 1;
    public const PAGE_ROOMS = 2;
    public const PAGE_ABOUT = 3;
    public const PAGE_CONTACT = 4;
    public const PAGE_RESERVATION = 5;
    public const PAGE_GLOBAL = 6;

    protected $guarded = [];

    public function medias(): BelongsToMany {
        return $this->belongsToMany(Media::class);
    }

    public function contents(): BelongsToMany {
        return $this->belongsToMany(Content::class, 'content_pages');
    }
}
