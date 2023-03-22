<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'address', 'phone_number'
    ];

    public function shop_users()
    {
        return $this->hasMany(ShopUser::class);
    }
    public function shop_payments()
    {
        return $this->hasMany(ShopPayment::class);
    }
    public function payments_from_company()
    {
        return $this->hasMany(TransactionsForShop::class);
    }
}
