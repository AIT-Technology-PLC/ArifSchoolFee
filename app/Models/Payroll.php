<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use MultiTenancy, Branchable, Approvable, HasUserstamps, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'starting_period' => 'date',
        'ending_period' => 'date',
    ];

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by')->withDefault(['name' => 'N/A']);
    }

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function pay()
    {
        $this->paid_by = authUser()->id;

        $this->save();
    }

    public function isPaid()
    {
        if (is_null($this->paid_by)) {
            return false;
        }

        return true;
    }

    public function scopePaid($query)
    {
        return $query->whereNotNull('paid_by');
    }

    public function scopeNotPaid($query)
    {
        return $query->whereNull('paid_by');
    }
}
