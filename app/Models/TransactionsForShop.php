<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionsForShop extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['shop_id','amount','image','type','paid_by'];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'paid_by', 'id')->withTrashed();
    }
    
}
