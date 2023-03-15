<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'rider_id', 'total_routine', 'total_amount'
    ];
}
