<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceIncrementDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function priceIncrement()
    {
        return $this->belongsTo(PriceIncrement::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
