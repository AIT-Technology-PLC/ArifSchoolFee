<?php

namespace App\Models;

use App\Models\MerchandiseBatch;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchandise extends Model
{
    use MultiTenancy, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = ['id'];

    protected $cascadeDeletes = [
        'merchandiseBatches',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', '>', 0);
    }

    public function getOnHandAttribute()
    {
        return number_format(
            $this->available + $this->reserved,
            2, '.', ''
        );
    }

    public function merchandiseBatches()
    {
        return $this->hasMany(MerchandiseBatch::class);
    }
}
