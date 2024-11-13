<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Employee extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('User');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function scopeDisabled($query)
    {
        return $query->where('enabled', 0);
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function isMale()
    {
        return $this->gender == 'male';
    }

    public function isFemale()
    {
        return $this->gender == 'female';
    }

    public static function getEmployees($excludeEmployeesOnLeave = true)
    {
        return User::getUsers($excludeEmployeesOnLeave)->pluck('employee');
    }

    public function toggle()
    {
        $this->isEnabled() ? $this->enabled = 0 : $this->enabled = 1;

        $this->save();
    }
}
