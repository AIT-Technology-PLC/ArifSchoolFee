<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;
use App\Traits\HasUserstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $casts = [
        'starting_period' => 'datetime',
        'ending_period' => 'datetime',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
}
