<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class ShopUser extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes;
    protected $guarded = [];
    protected $fillable = [
        'name', 'phone_number', 'email', 'email_verified_at', 'password', 'device_id','token', 'refresh_token'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function notifications()
    {
        return $this->morphToMany(Notification::class, 'notifiable');
    }
}
