<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Township extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'city_id'
    ];

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id')->withTrashed();
    }

    public function riders()
    {
        return $this->belongsToMany(Rider::class, 'rider_township', 'township_id', 'rider_id');
    }
    
    public function gates()
    {
        return $this->belongsToMany(Gate::class, 'gate_township', 'township_id', 'gate_id');
    }

    public function associable()
    {
        return $this->morphTo();
    }
}
