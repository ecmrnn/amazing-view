<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    public const STATUS_PARTIAL = 0;
    public const STATUS_PAID = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_DUE = 3;

    protected $guarded = [];

    public function payments(): HasMany {
        return $this->hasMany(InvoicePayment::class);
    }

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function discounts(): BelongsToMany {
        return $this->belongsToMany(Discount::class);
    }
    
    public static function boot()
    {
        // Generate custom ID: https://laravelarticle.com/laravel-custom-id-generator
        parent::boot();
        self::creating(function ($model) {
            $model->iid = IdGenerator::generate([
                'table' => 'invoices',
                'field' => 'iid',
                'length' => 10,
                'prefix' => 'I' . date('ymd'),
                'reset_on_prefix_change' => true
            ]);
        });
    }
}
