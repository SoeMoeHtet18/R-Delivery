<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofOfDelivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'image', 'delivered_date', 'rider_id', 'last_updated_by'
    ];
}
