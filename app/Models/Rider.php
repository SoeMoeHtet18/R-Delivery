<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Rider extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'name', 'phone_number', 'email', 'email_verified_at', 'password', 'device_id','token','refresh_token'
    ];
}
