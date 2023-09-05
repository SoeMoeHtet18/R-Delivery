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
        'remark', 'status', 'item_type_id', 'full_address', 'schedule_date', 'delivery_type_id', 'collection_method', 'proof_of_payment', 'last_updated_by', 'city_id', 'items', 'payment_flag',
        'is_payment_channel_confirm', 'branch_id', 'is_confirm', 'payable_or_not', 'pay_later', 'payment_method'
    ];

    public function rider() {
        return $this->belongsTo(Rider::class, 'rider_id', 'id')->withTrashed();
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id')->withTrashed();
    }

    public function township() {
        return $this->belongsTo(Township::class, 'township_id', 'id')->withTrashed();
    }

    public function user() {
        return $this->belongsTo(User::class, 'last_updated_by', 'id')->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->withTrashed();
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id', 'id')->withTrashed();
    }

    public function delivery_type()
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_type_id', 'id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id')->withTrashed();
    }

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Pending',
            'picking-up' => 'Picking Up',
            'warehouse' => 'Warehouse',
            'delivering' => 'Delivering',
            'success' => 'Delivered',
            'delay' => 'Delay',
            'cancel' => 'Cancel',
            'cancel-request' => 'Cancel Request'
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }
    
    public function getStatusLabelColorAttribute()
    {
        $statusLabels = [
            'pending' => 'warning',
            'picking-up' => 'warning',
            'warehouse' => 'warning',
            'delivering' => 'warning',
            'success' => 'success',
            'delay' => 'warning',
            'cancel' => 'danger',
            'cancel-request' => 'warning'
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    public function logs() {
        return $this->hasMany(Log::class);
    }

    public function successLog() {
        return $this->hasOne(Log::class)->where('to_status', 'success')->latest();
    }
}
