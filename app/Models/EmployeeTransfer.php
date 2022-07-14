<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTransfer extends Model
{
    use MultiTenancy, HasUserStamps, Approvable, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function employeeTransferDetails()
    {
        return $this->hasMany(EmployeeTransferDetail::class);
    }
}
