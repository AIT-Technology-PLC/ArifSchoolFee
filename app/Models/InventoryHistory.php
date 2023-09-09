<?php

namespace App\Models;

use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryHistory extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}