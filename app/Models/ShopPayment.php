<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id', 'amount', 'image', 'type'
    ];

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
}
