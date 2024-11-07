<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

/**
 * @method static latest()
 * @method static create(array $array)
 */
class FoodType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "food_types";
    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::forceDeleting(function ($foodType) {
            File::delete(storage_path(env('FOODTYPES_IMAGE_UPLOAD_PATH'). $foodType->image));
        });
    }

    public function scopeActive($query): void
    {
        $query->where('status', 1);
    }

    public function getStatusAttribute($status): string
    {
        return $status ? 'فعال' : 'غیرفعال';
    }
}
