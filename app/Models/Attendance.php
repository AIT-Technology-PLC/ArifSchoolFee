<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\Cancellable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use MultiTenancy, HasFactory, Branchable, Approvable, Cancellable, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function attendanceDetails()
    {
        return $this->hasMany(AttendanceDetail::class);
    }
}