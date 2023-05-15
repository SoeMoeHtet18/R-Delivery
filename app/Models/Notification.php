<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title','message'];

    public function riders()
    {
        return $this->morphedByMany(Rider::class, 'notifiable');
    }

    public function shopUsers()
    {
        return $this->morphedByMany(ShopUser::class, 'notifiable');
    }
}
