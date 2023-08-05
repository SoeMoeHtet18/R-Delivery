<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiderPayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'rider_id', 'total_routine', 'total_amount', 'branch_id', 'paid_date', 'type'
    ];

    public function rider() {
        return $this->belongsTo(Rider::class);
    }
}
