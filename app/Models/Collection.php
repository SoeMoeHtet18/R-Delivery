<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'total_quantity', 'total_amount', 'paid_amount', 'collection_group_id', 'rider_id', 'shop_id', 
        'assigned_at', 'collected_at', 'note', 'status', 'is_payable', 'branch_id', 'collection_code'
    ];

    public function collection_group() {
        return $this->belongsTo(CollectionGroup::class, 'collection_group_id', 'id')->withTrashed();
    }

    public function shop() 
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id')->withTrashed();
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id', 'id')->withTrashed();
    }
}
