<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPrepayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id', 'amount', 'paid_by'
    ];
}