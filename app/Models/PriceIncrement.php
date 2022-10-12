<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceIncrement extends Model
{
    use MultiTenancy, HasFactory, SoftDeletes, HasUserstamps, Approvable, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function PriceIncrementDetails()
    {
        return $this->hasMany(PriceIncrementDetail::class);
    }

    public function isUploadExcel()
    {
        return $this->target_product == 'Upload Excel';
    }

    public function isAllProducts()
    {
        return $this->target_product == 'All Products';
    }

    public function isTypePercent()
    {
        return $this->price_type == 'percent';
    }
}