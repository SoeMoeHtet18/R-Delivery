<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deficit extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'rider_id', 'total_amount'
    ];

    public function rider() {
        return $this->belongsTo(Rider::class,'rider_id','id');
    }
}
