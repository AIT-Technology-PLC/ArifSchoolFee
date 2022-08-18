<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCompensation extends Model
{
    protected $table = 'employee_compensations';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}