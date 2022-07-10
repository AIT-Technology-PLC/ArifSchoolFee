<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillOfMaterial extends Model
{
    use MultiTenancy, HasUserstamps, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function billOfMaterialDetails()
    {
        return $this->hasMany(BillOfMaterialDetail::class);
    }

    public function jobDetails()
    {
        return $this->hasMany(JobDetail::class);
    }

    public function details()
    {
        return $this->billOfMaterialDetails;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isUsedForProduction()
    {
        return $this
            ->jobDetails()
            ->where(function ($query) {
                $query->where('wip', '>', 0)
                    ->orWhere('available', '>', 0);
            })
            ->exists();
    }
}
