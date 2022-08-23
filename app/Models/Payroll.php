<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Payable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use MultiTenancy, Branchable, Approvable, Payable, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'starting_period' => 'date',
        'ending_period' => 'date',
    ];

    public function scopePayrollEnded()
    {
        return $this->ending_period <= Carbon::now();
    }
}