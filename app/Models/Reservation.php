<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const STATUS_CONFIRMED = 0;
    public const STATUS_PENDING = 1;
    public const STATUS_OK = 2;
    public const STATUS_DUE = 3;
    public const STATUS_PAID = 4;

    public function rooms(): BelongsToMany {
        return $this->BelongsToMany(Room::class, 'room_reservations');
    }

    public function user(): BelongsTo {
        return $this->BelongsTo(User::class);
    }

    public function amenities(): BelongsToMany {
        return $this->belongsToMany(Amenity::class, 'reservation_amenities');
    }

    public function invoice(): HasOne {
        return $this->hasOne(Invoice::class);
    }

    public static function boot()
    {
        // Generate custom ID: https://laravelarticle.com/laravel-custom-id-generator
        parent::boot();
        self::creating(function ($model) {
            $model->rid = IdGenerator::generate([
                'table' => 'reservations',
                'field' => 'rid',
                'length' => 12,
                'prefix' => date('Rmdy'),
                'reset_on_prefix_change' => true
            ]);
        });
    }
}
