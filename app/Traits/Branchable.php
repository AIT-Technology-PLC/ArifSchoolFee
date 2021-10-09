<?php

namespace App\Traits;

use App\Models\Warehouse;

trait Branchable
{
    protected static function bootBranchable()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->warehouse_id = auth()->user()->warehouse_id;
            }
        });
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeByBranch($query)
    {
        return $query->when(!user()->hasRole('System Manager'), function ($query) {
            return $query->where("{$this->getTable()}.warehouse_id", auth()->user()->warehouse_id);
        });

    }
}
