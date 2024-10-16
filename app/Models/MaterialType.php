<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static where(string $string, FoodType $foodType)
 * @method static active()
 */
class MaterialType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "material_types";

    protected $guarded = [];

    public function scopeActive($query): void
    {
        $query->where('status', 1);
    }

    public function getStatusAttribute($status): string
    {
        return $status ? 'فعال' : 'غیرفعال';
    }

    public function materials(): HasMany{
        return $this->hasMany(Material::class, 'type_id');
    }
}
