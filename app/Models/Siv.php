<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasCustomFields;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Subtractable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siv extends Model
{
    use MultiTenancy, Branchable, HasFactory, SoftDeletes, Approvable, HasUserstamps, HasCustomFields, Subtractable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function sivDetails()
    {
        return $this->hasMany(SivDetail::class);
    }

    public function sivable()
    {
        return $this->morphTo();
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by')->orHasMorph('sivable', '*');
    }

    public function scopeNotApproved($query)
    {
        return $query->whereNull('approved_by')->doesntHaveMorph('sivable', '*');
    }

    public function scopeSubtracted($query)
    {
        return $query->whereNotNull('subtracted_by')->orHasMorph('sivable', '*');
    }

    public function scopeNotSubtracted($query)
    {
        return $query->whereNull('subtracted_by')->doesntHaveMorph('sivable', '*');
    }

    public function isSubtracted()
    {
        if (is_null($this->subtracted_by) && !$this->sivable?->isSubtracted()) {
            return false;
        }

        return true;
    }

    public function isAssociated()
    {
        return !is_null($this->sivable_id);
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
