<?php

namespace App\Models;

use App\Models\CostUpdate;
use App\Models\Product;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostUpdateDetail extends Model
{
    use HasFactory, SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function costUpdate()
    {
        return $this->belongsTo(CostUpdate::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->costUpdate;
    }
}
