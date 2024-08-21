<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'rate',
        'description',
        'image_1_path',
        'image_2_path',
        'image_3_path',
        'image_4_path',
    ];

    public function rooms(): HasMany {
        return $this->hasMany(Room::class);
    }
}
