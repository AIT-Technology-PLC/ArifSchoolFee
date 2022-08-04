<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCompensation extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $table = 'employee_compensations';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}