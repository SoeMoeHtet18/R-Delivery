<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionGroup extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'total_amount', 'rider_id', 'assigned_date'
    ];

    public function collections() {
        return $this->hasMany(Collection::class)->withTrashed();
    }
}
