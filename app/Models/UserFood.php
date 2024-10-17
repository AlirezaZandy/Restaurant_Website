<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static create(array $array)
 */
class UserFood extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "user_foods";
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
