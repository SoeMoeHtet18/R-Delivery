<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_quantity', 'total_amount', 'paid_amount', 'collection_group_id', 'rider_id', 'shop_id', 'assigned_at', 'collected_at', 'note', 'status', 'is_payable'
    ];

    public function collection_gorup() 
    {
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
