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

    public function leaveCategory()
    {
        return $this->belongsTo(LeaveCategory::class);
    }
}