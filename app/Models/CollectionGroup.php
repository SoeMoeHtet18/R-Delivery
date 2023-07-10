<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_amount', 'rider_id', 'assigned_date'
    ];

    public function collections() {
        return $this->hasMany(Collection::class, 'id', 'collection_group_id')->withTrashed();
    }
}
