<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Staff extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'date_of_joining' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Staff Directory');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function staffCompensations()
    {
        return $this->hasOne(StaffCompensation::class);
    }

    public function messageDetails()
    {
        return $this->hasMany(MessageDetail::class);
    }
}
