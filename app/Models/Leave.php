<?php

namespace App\Models;

use App\Models\LeaveCategory;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\Cancellable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use MultiTenancy, SoftDeletes, Approvable, Branchable, Cancellable, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'starting_period' => 'datetime',
        'ending_period' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($leave) {
            if (auth()->check()) {
                $leave['code'] = nextReferenceNumber('leaves');
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveCategory()
    {
        return $this->belongsTo(LeaveCategory::class);
    }

    public function getLeavePeriodAttribute()
    {
        return number_format($this->ending_period->floatDiffInDays($this->starting_period), 2);
    }

    public function isPaidTimeOff()
    {
        return $this->is_paid_time_off == 1;
    }
}