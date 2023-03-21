<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsForShop extends Model
{
    use HasFactory;
    protected $fillable = ['shop_id','amount','image','type','paid_by'];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
    
}
