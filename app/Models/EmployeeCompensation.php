<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCompensation extends Model
{
    use SoftDeletes;

    protected $table = 'employee_compensations';

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
