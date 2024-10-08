<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SmsCode extends Model
{
    use HasFactory;
    protected $guarded = [];


    public static function checkTwoMinute($phone_number)
    {
        $check = self::query()->where('phone_number', $phone_number)->where('created_at' , '>', Carbon::now()->subMinute(2))->first();
        if ($check) {
            return true;

        } else {
            return false;
        }
    }
}