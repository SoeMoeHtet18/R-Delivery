<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'collection_group_id', 'order_id', 'items', 'paid_amount', 'is_way_fees_payable', 'item_image', 'quantities',
        'status', 'note', 'branch_id', 'customer_collection_code', 'shop_id','customer_name', 'customer_phone_number',
        'rider_id', 'city_id', 'township_id', 'address', 'schedule_date', 'pending_at', 'picking_at', 'warehouse_at',
        'complete_at'
    ];
    
    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withTrashed();
    }

    public function collection_group() {
        return $this->belongsTo(CollectionGroup::class, 'collection_group_id', 'id')->withTrashed();
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id')->withTrashed();
    }

    public function rider() {
        return $this->belongsTo(Rider::class, 'rider_id', 'id')->withTrashed();
    }
    
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->withTrashed();
    }

    public function township()
    {
        return $this->belongsTo(Township::class, 'township_id', 'id')->withTrashed();
    }
}
