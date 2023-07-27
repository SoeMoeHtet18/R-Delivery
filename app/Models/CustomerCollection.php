<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_collection_code', 'customer_name', 'rider_id', 'shop_id', 'order_id', 'items', 'paid_amount', 'is_way_payable', 'item_image', 'quantity',
        'status', 'notes', 
    ];

    public function rider() {
        return $this->belongsTo(Rider::class, 'rider_id', 'id')->withTrashed();
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id')->withTrashed();
    }
    
    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withTrashed();
    }
}
