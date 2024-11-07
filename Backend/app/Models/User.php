<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public static function checkTwoMinute($phone_number)
    {
        $check = self::query()->where('phone_number', $phone_number)->where('created_at' , '>', Carbon::now()->subMinute(2))->first();
        if ($check) {
            return true;

        } else {
            return false;
        }
    }

    public static function checkCode($phone_number , $code , $login_token){
        $checkCode = self::query()->where([
            'phone_number' => $phone_number,
            'code' => $code,
            'login_token' => $login_token,
        ])->first();

        if($checkCode){
            return true;

        }else{
            return false;
        }

    }
}