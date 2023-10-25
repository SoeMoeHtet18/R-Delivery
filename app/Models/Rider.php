<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Rider extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes;
    protected $fillable = [
        'name', 'phone_number', 'email', 'email_verified_at', 'password', 'device_id','token','refresh_token',
         'branch_id'
    ];

    public function townships()
    {
        return $this->belongsToMany(Township::class, 'rider_township','rider_id','township_id')->withPivot('rider_fees')->withTrashed();
    }

    public function notifications()
    {
        return $this->morphToMany(Notification::class, 'notifiable');
    }

    public function deficits()
    {
        return $this->hasMany(Deficit::class);
    }

    public function loggable()
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
