<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'rider_id'
    ];

    public function city() {
        return $this->belongsTo(City::class, 'rider_id', 'id');
    }
}
