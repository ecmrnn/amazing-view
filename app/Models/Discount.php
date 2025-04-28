<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    public const STATUS_INACTIVE = 1;
    public const STATUS_ACTIVE = 2;

    protected $guarded = [];

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function attachments(): HasMany {
        return $this->hasMany(DiscountAttachment::class);
    }
}
