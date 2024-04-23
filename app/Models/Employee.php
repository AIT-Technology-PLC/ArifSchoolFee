<?php

namespace App\Models;

use App\Models\Department;
use App\Models\EmployeeCompensationHistory;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'date_of_hiring' => 'datetime',
        'date_of_birth' => 'datetime',
        'enabled' => 'boolean',
        'does_receive_sales_report_email' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employeeTransferDetails()
    {
        return $this->hasMany(EmployeeTransferDetail::class);
    }

    public function warnings()
    {
        return $this->hasMany(Warning::class);
    }

    public function advancementDetails()
    {
        return $this->hasMany(AdvancementDetail::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function scopeDisabled($query)
    {
        return $query->where('enabled', 0);
    }

    public function scopeSalesReportEmailRecipent($query)
    {
        return $query->enabled()->where('does_receive_sales_report_email', 1);
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function isMale()
    {
        return $this->gender == 'male';
    }

    public function attendanceDetails()
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function isFemale()
    {
        return $this->gender == 'female';
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function expenseClaims()
    {
        return $this->hasMany(ExpenseClaim::class);
    }

    public function employeeCompensations()
    {
        return $this->hasMany(EmployeeCompensation::class);
    }

    public function compensationAdjustmentDetails()
    {
        return $this->hasMany(CompensationAdjustmentDetail::class);
    }

    public function employeeCompensationHistories()
    {
        return $this->hasMany(EmployeeCompensationHistory::class);
    }

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function isOnLeave(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->leaves()->approved()->where('ending_period', '>', now())->exists()
        )->shouldCache();
    }

    public function absentDays(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attendanceDetails()->whereHas('attendance', fn($q) => $q->approved()->latest('ending_period'))->first()->days ?? null
        )->shouldCache();
    }

    public static function getEmployees($excludeEmployeesOnLeave = true)
    {
        return User::getUsers($excludeEmployeesOnLeave)->pluck('employee');
    }

    public function doesReceiveSalesReportEmail()
    {
        return $this->does_receive_sales_report_email;
    }
}
