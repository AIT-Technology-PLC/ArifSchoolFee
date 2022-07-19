<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use MultiTenancy, HasFactory, Branchable, Approvable;

    public function attendanceDetails()
    {
        return $this->hasMany(AttendanceDetail::class);
    }
}