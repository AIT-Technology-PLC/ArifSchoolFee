<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use MultiTenancy, Branchable, HasUserStamps, Approvable, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withDefault(['name' => 'N/A']);
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
        return $this->hasMany(JobDetail::class);
    }

    public function jobExtras()
    {
        return $this->hasMany(JobExtra::class);
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
}
