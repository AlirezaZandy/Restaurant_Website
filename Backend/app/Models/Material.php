<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static collection($materialTypes)
 * @method static create(array $array)
 * @method static where(string $string, $id)
 */
class Material extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "materials";

    protected $guarded = [];

    public function scopeActive($query): void
    {
        $query->where('status', 1);
    }

    public function getStatusAttribute($status): string
    {
        return $status ? 'فعال' : 'غیرفعال';
    }
}
