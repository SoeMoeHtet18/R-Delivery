<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'city_id', 'address', 'phone_number'
    ];

    public function city() {
        return $this->belongsTo(City::class);
    }
}
