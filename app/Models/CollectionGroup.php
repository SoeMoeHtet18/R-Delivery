<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionGroup extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'total_amount', 'rider_id', 'assigned_date', 'branch_id', 'collection_group_code',
        'total_quantity', 'total_collection'
    ];

    public function collections() {
        return $this->hasMany(Collection::class)->withTrashed();
    }

    public function rider() {
        return $this->belongsTo(Rider::class);
    }

    public function customer_collections() {
        return $this->hasMany(CustomerCollection::class)->withTrashed();
    }
}
