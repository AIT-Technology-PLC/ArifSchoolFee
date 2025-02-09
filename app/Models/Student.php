<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Student extends Model
{
    use HasFactory, MultiTenancy, SoftDeletes, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'admission_date' => 'datetime',
    ];

    protected $cascadeDeletes = [
        'studentHistories',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Student');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function studentCategory()
    {
        return $this->belongsTo(StudentCategory::class);
    }

    public function studentGroup()
    {
        return $this->belongsTo(StudentGroup::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function messageDetails()
    {
        return $this->hasMany(MessageDetail::class);
    }

    public function assignFeeMasters()
    {
        return $this->belongsToMany(FeeMaster::class, 'assign_fee_masters', 'student_id', 'fee_master_id');
    }

    public function assignFeeMasterRecords()
    {
        return $this->hasMany(AssignFeeMaster::class);
    }

    public function assignFeeDiscounts()
    {
        return $this->belongsToMany(FeeDiscount::class, 'assign_fee_discounts', 'student_id', 'fee_discount_id');
    }

    public function assignFeeDiscountRecords()
    {
        return $this->hasMany(AssignFeeDiscount::class);
    }

    public function isAssignedToFeeMaster($feeMasterId)
    {
        return $this->assignFeeMasters()->where('fee_master_id', $feeMasterId)->exists();
    }

    public function isAssignedToFeeDiscount($feeDiscountId)
    {
        return $this->assignFeeDiscounts()->where('fee_discount_id', $feeDiscountId)->exists();
    }

    public function studentHistories()
    {
        return $this->hasMany(StudentHistory::class);
    }

    public function latestStudentHistoryId()
    {
        return $this->studentHistories()->latest('created_at')->value('id');    
    }

    public function hasUnpaidFees()
    {
        return $this->assignFeeMasterRecords()->whereDoesntHave('feePayments')->exists();
    }
}