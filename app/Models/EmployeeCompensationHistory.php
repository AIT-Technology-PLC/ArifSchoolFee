<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Compensation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCompensationHistory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function compensation()
    {
        return $this->belongsTo(Compensation::class);
    }
}