<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'order_code', 'customer_name', 'customer_phone_number', 'township_id', 'rider_id', 'shop_id', 'quantity', 'total_amount', 'delivery_fees', 'markup_delivery_fees',
        'remark', 'status', 'item_type', 'full_address', 'schedule_date', 'type', 'collection_method', 'proof_of_payment', 'last_updated_by'
    ];

    public function rider() {
        return $this->belongsTo(Rider::class, 'rider_id', 'id');
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function township() {
        return $this->belongsTo(Township::class, 'township_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'last_updated_by', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
