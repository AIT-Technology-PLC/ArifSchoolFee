<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function parentModel()
    {
        return $this->attendance();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}