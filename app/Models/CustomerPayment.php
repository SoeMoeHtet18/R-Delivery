<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPayment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'order_id', 'amount', 'type', 'proof_of_payment', 'paid_at', 'last_updated_by'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
