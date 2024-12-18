<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;
use App\Traits\HasUserstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AcademicYear extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, LogsActivity;

    protected $casts = [
        'starting_period' => 'datetime',
        'ending_period' => 'datetime',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Academic Year');
    }
    
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function studentHistories()
    {
        return $this->hasMany(StudentHistory::class);
    }
}
