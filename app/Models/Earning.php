<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Earning extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, Approvable, Branchable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'starting_period' => 'datetime',
        'ending_period' => 'datetime',
    ];

    public function earningDetails()
    {
        return $this->hasMany(EarningDetail::class);
    }
}
