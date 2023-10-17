<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adjustment extends Model
{
    use MultiTenancy, Branchable, HasFactory, Approvable, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by')->withDefault(['name' => 'N/A']);
    }

    public function adjustmentDetails()
    {
        return $this->hasMany(AdjustmentDetail::class);
    }

    public function scopeAdjusted($query)
    {
        return $query->whereNotNull('adjusted_by');
    }

    public function scopeNotAdjusted($query)
    {
        return $query->whereNull('adjusted_by');
    }

    public function adjust()
    {
        $this->adjusted_by = authUser()->id;

        $this->save();
    }

    public function isAdjusted()
    {
        if (is_null($this->adjusted_by)) {
            return false;
        }

        return true;
    }

    public function canAffectInventoryValuation()
    {
        return true;
    }

    public function canReverseInventoryValuation()
    {
        return false;
    }
}
