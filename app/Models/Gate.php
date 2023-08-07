<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gate extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'city_id', 'address'
    ];

    public function townships()
    {
        return $this->belongsToMany(Township::class, 'gate_township','gate_id','township_id')->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->withTrashed();
    }
}
