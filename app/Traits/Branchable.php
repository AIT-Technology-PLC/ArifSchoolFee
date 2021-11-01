<?php

namespace App\Traits;

use App\Models\Warehouse;
use App\Scopes\BranchScope;

trait Branchable
{
    protected static function bootBranchable()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->warehouse_id = auth()->user()->warehouse_id;
            }
        });

        if (static::withBranchScope()) {
            static::addGlobalScope(new BranchScope);
        }
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeByBranch($query)
    {
        return $query->where("{$this->getTable()}.warehouse_id", auth()->user()->warehouse_id);
    }

    public static function withBranchScope()
    {
        return true;
    }
}
