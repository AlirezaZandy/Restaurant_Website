<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
