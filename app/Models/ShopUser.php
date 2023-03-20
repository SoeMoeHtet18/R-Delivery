<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'phone_number', 'email', 'email_verified_at', 'password', 'device_id'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
