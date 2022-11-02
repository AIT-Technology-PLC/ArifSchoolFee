<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compensation extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $table = 'compensations';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function isEarning()
    {
        return $this->type == 'earning';
    }

    public function isActive()
    {
        return $this->is_active == 1;
    }

    public function isTaxable()
    {
        return $this->is_taxable == 1;
    }

    public function isAdjustable()
    {
        return $this->is_adjustable == 1;
    }

    public function canBeInputtedManually()
    {
        return $this->can_be_inputted_manually == 1;
    }

    public function scopeDeductions($query)
    {
        return $query->where('type', 'deduction');
    }

    public function scopeEarnings($query)
    {
        return $query->where('type', 'earning');
    }

    public function scopeCanBeInputtedManually($query)
    {
        return $query->where('can_be_inputted_manually', 1);
    }

    public function scopeAdjustable($query)
    {
        return $query->where('is_adjustable', 1);
    }

    public function employeeCompensationHistories()
    {
        return $this->hasMany(EmployeeCompensationHistory::class);
    }
}
