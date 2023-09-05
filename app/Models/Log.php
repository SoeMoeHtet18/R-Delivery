<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'from_status', 'to_status', 'updated_by', 'updated_type'];

    public function loggable()
    {
        return $this->morphTo();
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
