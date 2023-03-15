<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_code', 'customer_name', 'customer_phone_number', 'township_id', 'rider_id', 'shop_id', 'quantity', 'total_amount', 'delivery_fees', 'markup_delivery_fees',
        'remark', 'status', 'item_type', 'full_address', 'schedule_date', 'type', 'collection_method', 'proof_of_payment', 'last_updated_by'
    ];
}
