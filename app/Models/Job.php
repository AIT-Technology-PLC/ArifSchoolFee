<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use MultiTenancy, Branchable, HasUserStamps, Approvable, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
    ];

    protected $table = 'job_orders';

    protected static function booted()
    {
        static::addGlobalScope('activeFactories', function (Builder $builder) {
            if (auth()->check()) {
                $builder->whereIn('factory_id', Warehouse::pluck('id')->merge(authUser()->warehouse->isActive() ? authUser()->warehouse_id : null));
            }
        });
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function factory()
    {
        return $this->belongsTo(Warehouse::class, 'factory_id');
    }

    public function jobDetails()
    {
        return $this->hasMany(JobDetail::class, 'job_order_id');
    }

    public function jobExtras()
    {
        return $this->hasMany(JobExtra::class, 'job_order_id');
    }

    public function getJobCompletionRateAttribute()
    {
        $completedJobs = $this->jobDetails->sum('available');

        $inProcessJobs = $this->jobDetails->sum('wip') * 0.5;

        $totalJobDetails = $this->jobDetails->sum('quantity') ?? 0.00;

        if (!$totalJobDetails) {
            return 100.00;
        }

        $jobCompletionRate = ($completedJobs + $inProcessJobs) / $totalJobDetails;

        if ($jobCompletionRate == 1) {
            return 100.00;
        }

        return number_format($jobCompletionRate * 100, 2);
    }

    public function isStarted()
    {
        return $this->jobCompletionRate > 0;
    }

    public function isCompleted()
    {
        return $this->jobCompletionRate == 100;
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal_job', 1);
    }

    public function scopeNotInternal($query)
    {
        return $query->where('is_internal_job', 0);
    }

    public function isInternal()
    {
        return $this->is_internal_job;
    }

    public function close()
    {
        $this->closed_by = auth()->id();

        $this->save();
    }

    public function isClosed()
    {
        if (is_null($this->closed_by)) {
            return false;
        }

        return true;
    }

    public function getForecastedCompletionRateAttribute()
    {
        $assignedDays = $this->due_date->diffInDays($this->issued_on) + 1;
        $completedDays = now()->diffInDays($this->issued_on) + 1;

        $dailyProductionGoal = number_format(100 / $assignedDays, 2);

        return number_format($dailyProductionGoal * $completedDays, 2);
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