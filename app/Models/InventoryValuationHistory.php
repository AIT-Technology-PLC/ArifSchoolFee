<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryValuationHistory extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}