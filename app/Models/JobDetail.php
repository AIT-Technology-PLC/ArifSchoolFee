<?php

namespace App\Models;

use App\Models\BillOfMaterial;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    public function scopeWip($query)
    {
        return $query->where('wip', '>', 0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', '>', 0);
    }

    public function parentModel()
    {
        return $this->job;
    }

    public function isAvailableCompleted()
    {
        return $this->quantity == $this->available;
    }

    public function isWipCompleted()
    {
        return $this->quantity == $this->wip;
    }

    public function isJobDetailCompleted()
    {
        return $this->quantity == $this->wip + $this->available;
    }

    public function getCompletionRateAttribute()
    {
        $availableQuantity = $this->available;

        $wipQuantity = $this->wip * 0.5;

        $totalQuantity = $this->quantity ?? 0.00;

        if (!$totalQuantity) {
            return 100.00;
        }

        $completionRate = ($availableQuantity + $wipQuantity) / $totalQuantity;

        if ($completionRate == 1) {
            return 100.00;
        }

        return number_format($completionRate * 100, 2);
    }
}
