<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollDetail extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function compensation()
    {
        return $this->belongsTo(Compensation::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
