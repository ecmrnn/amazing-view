<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory;
    public const STATUS_ACTIVE = 0;
    public const STATUS_INACTIVE = 1;

    protected $guarded = [];

    public function rooms(): HasMany {
        return $this->hasMany(Room::class);
    }
}
