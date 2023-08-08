<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThirdPartyVendor extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'type', 'address'
    ];

    public function townships()
    {
        return $this->morphMany(Township::class, 'associable');
    }
}
