<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopPayment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'shop_id', 'amount', 'image', 'type', 'branch_id'
    ];

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id')->withTrashed();
    }
}
