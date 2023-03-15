<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'amount', 'type', 'proof_of_payment', 'paid_at', 'last_updated_by'
    ];
}
