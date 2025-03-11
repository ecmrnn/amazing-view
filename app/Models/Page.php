<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mediaFiles(): HasMany {
        return $this->hasMany(MediaFile::class);
    }

    public function contents(): HasMany {
        return $this->hasMany(PageContent::class);
    }
}
