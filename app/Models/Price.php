<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use MultiTenancy, HasUserstamps, SoftDeletes, HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeFixed($query)
    {
        return $query->where('type', 'fixed');
    }

    public function scopeRange($query)
    {
        return $query->where('type', 'range');
    }
}
