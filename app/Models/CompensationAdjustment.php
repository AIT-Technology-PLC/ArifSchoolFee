<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\Cancellable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompensationAdjustment extends Model
{
    use MultiTenancy, HasFactory, Branchable, Approvable, Cancellable, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'starting_period' => 'datetime',
        'ending_period' => 'datetime',
    ];

    public function compensationAdjustmentDetails()
    {
        return $this->hasMany(CompensationAdjustmentDetail::class);
    }
}